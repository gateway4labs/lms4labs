Usage: Moodle administrator
===========================

Once Moodle 2.x is installed, you must copy the directory "lms4labs" in the
"blocks" directory in the Moodle installation. This way, in the blocks directory
there should be existing blocks such as "blog_menu" or "course_list", and
a directory called "lms4labs" with files such as "util.php" or "addlms4labs.php"
inside.

Once it is copied, LMS administrator will log in the system. The web browser will
show the block "lms4labs" and it can be installed:

.. image:: /_static/moodle2_3_install1.png
   :width: 500px
   :align: center

The LMS administrator will click on "Upgrade Moodle database now", and the it
will be installed and reported as follows:

.. image:: /_static/moodle2_3_install2.png
   :width: 500px
   :align: center

From this point, the plug-in is installed, but not configured. So as to
configure it, the LMS administrator has to click on "Turn editing on":

.. image:: /_static/moodle2_3_configure2.png
   :width: 500px
   :align: center

Once editing, in the "Add a block" block, you can select "lms for labs". Once it
is selected, a new block called "Lms for labs" is added in a sidebar. There, the
administrator must click on in the link "Add data for LabManager"

.. image:: /_static/moodle2_3_configure3.png
   :width: 500px
   :align: center

Then, the configuration form is displayed to collect the configuration data:

.. image:: /_static/moodle2_3_configuration4.png
   :width: 500px
   :align: center

Five fields are required: the URL, which will be something like
http//localhost:5000/lms4labs *(without trailing slash)*, and two pairs of
credentials, called "Labmanager user" and "Labmanager password" and "LMS user"
and "LMS password". The name is misleading since it may seem that "LMS user" is
a user of the LMS; however, they are just two credentials that make it possible
to authenticate one system in the other. Typically, LMS user will be something
like "myuniversity", and the password a secret that the LMS will provide each
time it connects to the LabManager. In the same way, LabManager user will
typically be something like "labmanager", and the password a secret that the
LabManager will send in each request to the LMS.

In the image, it says "Configuration successfully validated!". Whenever the LMS
administrator enters in this configuration page, a validation process is
performed. If you have not configured the LabManager yet, an error might be
normal. However, retry the validation process later so as to ensure that
everything is correctly configured.

From this point, the plug-in is correctly installed and configured. However, in
the LabManager no permission has been granted to any course. So, the next step
is connecting to the LabManager and configuring the courses and permissions on
courses. So as to do this, the LabManager administrator must send a SCORM
package to the LMS administrator.

The LMS administrator can upload the authentication SCORM package in any course.
To do this, the administrator must log in a course and select scorm from the
"activity" list box and add file "authenticate.zip".

.. image:: /_static/moodle_authentication.png
   :width: 500px
   :align: center

.. image:: /_static/moodle2_3_authentication2.png
   :width: 500px
   :align: center

Once uploaded, the administrator can display the SCORM package, and it will show
a message such as follows with a link that enables the administrator to log in
the LabManager directly:

.. image:: /_static/moodle2_3_authentication3.png
   :width: 500px
   :align: center

Once in the LabManager authenticated as a LMS administrator, no course will be
listed the first time. It is mandatory to click on "Add" to add the courses:

.. image:: /_static/moodle2_3_addcourse.png
   :width: 500px
   :align: center

And in that list, it is possible to grant which courses can use which
laboratories (of those enabled by the LabManager administrator for the current
LMS):

.. image:: /_static/labmanager_lms_grant_permissions.png
   :width: 500px
   :align: center

Finally, clicking on SCORM in the navigation panel, it is possible to download
SCORM packages for each enabled laboratory and send them to the teachers:

.. image:: /_static/labmanager_download_scorms.png
   :width: 500px
   :align: center
