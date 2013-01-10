# -*-*- encoding: utf-8 -*-*-
# 
# lms4labs is free software: you can redistribute it and/or modify
# it under the terms of the BSD 2-Clause License
# lms4labs is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

"""
  :copyright: 2012 Pablo Orduña, Elio San Cristobal, Alberto Pesquera Martín
  :license: BSD, see LICENSE for more details
"""

#
# Python imports
import hashlib
import json
import cgi
import traceback
from functools import wraps
from sets import Set
from yaml import load as yload
# 
# Flask imports
# 
from flask import Response, render_template, request, g, redirect, url_for, session

# 
# LabManager imports
# 
from labmanager.database import db_session
from labmanager.models   import LMS, PermissionOnLaboratory, Course, PermissionOnCourse
from labmanager.rlms     import get_manager_class

from labmanager import app
from labmanager.views import get_json

from ims_lti_py import ToolProvider

config = yload(open('labmanager/config.yaml'))
###############################################################################
# 
# 
#
#               I N T E R A C T I O N     W I T H     L M S 
#
# 
# 

# 
# LMS authentication
# 
def check_lms_auth(lmsname, password):

    if 'oauth_consumer_key' in request.form:
        lms = db_session.query(LMS).filter_by(lms_login = lmsname).first()
        # check for nonce
        # check for old requests

        if lms is None:
            return None

        secret = lms.lms_password
        tool_provider = ToolProvider(lmsname, secret, request.form.to_dict())

        if (tool_provider.valid_request(request) == False):
            return None
        hash_password = secret

    else:
        hash_password = hashlib.new("sha", password).hexdigest()

    lms = db_session.query(LMS).filter_by(lms_login = lmsname, lms_password = hash_password).first()
    g.lms = lmsname
    session['lms'] = lmsname
    return lms is not None

def requires_lms_auth(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        login_required = Response(
                    'Could not verify your access level for that URL.\n'
                    'You have to login with proper credentials', 401,
                    {'WWW-Authenticate': 'Basic realm="Login Required"'})

        oauth = None
        try:
            oauth = request.form['oauth_consumer_key']
        except:
            auth = request.authorization
            if not auth:
                json_data = get_json()
                if json_data is None:
                    return login_required
                username = json_data.get('lms_username','')
                password = json_data.get('lms_password','')
            else:
                username = auth.username
                password = auth.password
        else:
            username = request.form['oauth_consumer_key']
            password = ""

        if not check_lms_auth(username, password):
            return login_required

        return f(*args, **kwargs)
    return decorated

@app.route("/lms4labs/labmanager/requests/", methods = ['GET', 'POST'])
@requires_lms_auth
def requests():
    """SCORM packages will perform requests to this method, which will 
    interact with the permitted laboratories"""

    if request.method == 'GET':
        return render_template("test_requests.html")

    json_data = get_json()
    if json_data is None: return "Could not process JSON data"

    courses             = json_data['courses']
    request_payload_str = json_data['request-payload']
    general_role        = json_data.get('is-admin', False)
    author              = json_data['user-id']
    complete_name       = json_data['full-name']
    user_agent          = json_data.get('user-agent', 'unknown user agent')
    origin_ip           = json_data.get('origin-ip', 'unknown IP address')
    referer             = json_data.get('referer', 'unknown referer')

    try:
        request_payload = json.loads(request_payload_str)
    except:
        traceback.print_exc()
        return "error: the request payload is not a valid JSON request"

    try:
        action = request_payload['action']
        if action == 'reserve':
            experiment_identifier = request_payload['experiment']
        else:
            # TODO: other operations: for teachers, etc.
            return "Unsupported operation"
    except KeyError:
        traceback.print_exc()
        return "Invalid response"

    # reserving...
    db_lms = db_session.query(LMS).filter_by(lms_login = g.lms).first()
    permission_on_lab = db_session.query(PermissionOnLaboratory).filter_by(lms_id = db_lms.id, local_identifier = experiment_identifier).first()
    good_msg  = "No good news :-("
    error_msg = None
    if permission_on_lab is None:
        error_msg = "Your LMS does not have permission to use that laboratory or that identifier does not exist"
    else:
        courses_configurations = []
        for course_permission in permission_on_lab.course_permissions:
            if course_permission.course.course_id in courses:
                # Let the server choose among the best possible configuration
                courses_configurations.append(course_permission.configuration)
        if len(courses_configurations) == 0 and not general_role:
            error_msg = "Your LMS has permission to use that laboratory; but you are not enrolled in any course with permissions to use it"
        else:
            lms_configuration = permission_on_lab.configuration
            db_laboratory   = permission_on_lab.laboratory
            db_rlms         = db_laboratory.rlms
            db_rlms_version = db_rlms.rlms_version
            db_rlms_type    = db_rlms_version.rlms_type

            ManagerClass = get_manager_class(db_rlms_type.name, db_rlms_version.version)
            remote_laboratory = ManagerClass(db_rlms.configuration)
            reservation_url = remote_laboratory.reserve(db_laboratory.laboratory_id, author, lms_configuration, courses_configurations, request_payload, user_agent, origin_ip, referer)

            good_msg = "You have been assigned %s of type %s version %s! <br/> Try it at <a href='%s'>%s</a>" % (db_rlms.name, db_rlms_type.name, db_rlms_version.version, reservation_url, reservation_url)

    if app.config.get('DEBUGGING_REQUESTS', True):
        courses_code = "<table><thead><tr><th>Course ID</th><th>Role</th></tr></thead><tbody>\n"
        for course_id in courses:
            role_in_course = courses[course_id]
            courses_code += "<tr><td>%s</td><td>%s</td></tr>\n" % (course_id, role_in_course)
        courses_code += "</tbody></table>"

        return """Hi %(name)s (username %(author)s),

            <p>I know that you're an admin ( %(admin)s ) in the LMS %(lms)s, and that you are in the following courses:</p>
            <br/>
            %(course_code)s
            <br/>
            <p>The following error messages were sent: %(error_msg)s</p>
            <p>The following good messages were sent: %(good_msg)s</p>

            Furthermore, you sent me this request:
            <pre>
            %(request)s
            </pre>
            
            And I'll process it!

            Original request:
            <pre> 
            %(json)s
            </pre>
        """ % {
            'name'        : cgi.escape(complete_name),
            'author'      : cgi.escape(author),
            'lms'         : cgi.escape(g.lms),
            'course_code' : courses_code,
            'request'     : cgi.escape(request_payload_str),
            'admin'        : general_role,
            'json'        : cgi.escape(json.dumps(json_data)),
            'error_msg'   : cgi.escape(error_msg or 'no error message'),
            'good_msg'    : good_msg or 'no good message',
        }
    else:
        if error_msg is None:
            return reservation_url
        else:
            return 'error:%s' % error_msg

@app.route("/lti/admin/", methods = ['POST'])
@requires_lms_auth
def admin_ims():
    response = None

    consumer_key = request.form['oauth_consumer_key']
    db_lms = db_session.query(LMS).filter_by(lms_login = g.lms).first()
    context_id = request.form['context_id']
    context_label = request.form['context_label']

    data = { 'user_agent' : request.user_agent,
             'origin_ip' : request.remote_addr,
             'lms' : db_lms.name,
             'lms_id' : db_lms.id,
             'context_label' : context_label,
             'context_id' : context_id,
             }

    # Defined by the standard. After this comes the role of the user as in
    # 'urn:lti:sysrole:ims/lis/Administrator' or 'urn:lti:sysrole:ims/lis/SysAdmin'
    urn_role_base = 'urn:lti:sysrole:ims/lis/'
    roles = Set()

    split_roles = request.form['roles'].split(',')
    for role in split_roles:
        if role.startswith(urn_role_base):
            roles.add(role[len(urn_role_base):])

    admin_roles = Set(config['standard_urn_admin_roles'])
    current_users_roles = roles & admin_roles # Set intersection

    if len(current_users_roles) > 0:
        data['role'] = current_users_roles.pop() # Returns an arbitrary element
        data['rlms'] = {}
        data['rlms_ids'] = {}

        db_course = db_session.query(Course).filter_by(lms = db_lms, course_id = context_id).first()
        if db_course is None:
            db_course = Course(db_lms, context_id, context_label)
            db_session.add(db_course)
            db_session.commit()

        response = redirect(url_for('lms_admin_courses_permissions', course_id = db_course.id))

    else:
        data['role'] = split_roles[0]
        response = render_template('lti/unknown_role.html', info=data)

    return response


@app.route("/lti/", methods = ['POST'])
@requires_lms_auth
def start_ims():
    response = ""

    current_role = Set(request.form['roles'].split(','))
    req_course_data = request.form['lis_result_sourcedid']
    db_lms = db_session.query(LMS).filter_by(lms_login = g.lms).first()
    context_id = request.form['context_id']

    data = { 'user_agent' : request.user_agent,
             'origin_ip' : request.remote_addr,
             'lms' : g.lms,
             'lms_id' : db_lms.id,
             'context_label' : request.form['context_label'],
             'context_id' : context_id,
             'access' : False
             }

    db_course = db_session.query(Course).filter_by(lms = db_lms, course_id = context_id).first()
    course_experiments = db_session.query(PermissionOnCourse).filter_by(course_id = db_course.id).all()

    data['context_experiments'] = [exp.permission_on_lab for exp in course_experiments]

    if ('Instructor' in current_role):
        data['role'] = 'Instructor'

        if course_experiments:
            response = render_template('lti/experiments.html', info=data)
        else:
            response = render_template('lti/instructions.html', info=data)

    elif ('Learner' in current_role):
        data['role'] = 'Learner'

        if course_experiments:
            response = render_template('lti/experiments.html', info=data)
        else:
            response = render_template('lti/no_experiments_info.html', info=data)

    else:
        response = render_template('lti/unknown_role.html', info=data)

    return response

@app.route("/lti/experiment/", methods = ['POST'])
def lti_launch_experiment():

    experiment_identifier = None
    try:
        experiment_identifier = request.form['identifier']
    except:
        return "Reload and select an experiment"

    db_lms = db_session.query(LMS).filter_by(lms_login = session['lms']).first()
    permission_on_lab = db_session.query(PermissionOnLaboratory).filter_by(lms_id = db_lms.id, local_identifier = experiment_identifier).first()

    courses_configurations = []
    courses = []
    for course_permission in permission_on_lab.course_permissions:
        if course_permission.course.course_id in courses:
            # Let the server choose among the best possible configuration
            courses_configurations.append(course_permission.configuration)
    lms_configuration = permission_on_lab.configuration
    db_laboratory   = permission_on_lab.laboratory
    db_rlms         = db_laboratory.rlms
    db_rlms_version = db_rlms.rlms_version
    db_rlms_type    = db_rlms_version.rlms_type
    user_agent = str(request.user_agent)
    origin_ip = request.remote_addr
    author = ""
    referer = ""

    ManagerClass = get_manager_class(db_rlms_type.name, db_rlms_version.version)
    remote_laboratory = ManagerClass(db_rlms.configuration)
    reservation_url = remote_laboratory.reserve(db_laboratory.laboratory_id, author, lms_configuration, courses_configurations, user_agent, origin_ip, referer)
    return redirect(reservation_url)
