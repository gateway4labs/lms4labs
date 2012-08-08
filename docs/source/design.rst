Design overview
===============

Introduction
~~~~~~~~~~~~

lms4labs is a pragmatic approach targeting the integration of multiple RLMSs
(Remote Laboratory Management Systems) in different LMSs (Learning Management
Systems). There are few principles:

1. Supporting a new LMS or LMS version must be simple and easy.

2. The solution must be secure. A student should never be able to open a lab
   session unles the LMS grants him access to it.

3. It must work with LMSs used by users, as opposed to only supporting future
   versions of LMSs with standards not yet implemented.

4. It must work with LMSs in a variety of situations, from universities
   supporting Single Sign-On solutions -such as `Shibboleth
   <http://shibboleth.net/>`_- or directory protocols -such as LDAP- to
   secondary schools with almost no IT infrastructure.

5. IT services of entities deploying lms4labs must be able to audit quickly
   whatever involves the LMS.

6. It must work with multiple RLMSs, so they can collaborate towards the same
   shared goal. While some code must be RLMS-dependent, most of the code should
   be common and RLMS-independent.

Given that multiple LMSs are targeted, ideally there should be no LMS dependent
code. There are approaches that try to achieve this. However:

* Some of them do not guarantee that any malicious user can open a lab session
  without the LMS supporting it. This is in conflict with principle number 2.

* Some of them rely on protocols not yet supported by existing LMSs, such as
  Shibboleth or IMS LTI. This is in conflict with principles number 1 (if the LMS
  does not support it, including it becomes complex), 3 and 4 (if the secondary
  school does not support that protocol, the solution will not work).
  Additionally, given that these protocols do not know what the LMS itself
  states, certain common situations are not covered. For instance, instructors may
  want to establish which weeks lab sessions are available and which ones are
  not.

Therefore, we accept that there is some LMS-dependent code as the lesser of two
evils. However, so as to support the principles mentioned above, this code:

* Should be as small and simple as possible: IT services must be able to audit
  it quickly.

* Should not require upgrades too often: that will typically involve critical,
  slow processes by the IT services.

Most of the logic should therefore be located in other component. This component
has been named *labmanager*, as detailed in the following section.

Architecture
~~~~~~~~~~~~

.. image:: /_static/general_architecture.png
   :scale: 50
   :align: center

As described in the figure above, there are three main components involved:

#. The LMS, which will have a small plug-in that communicates with the
   LabManager.

#. The LabManager, which will receive requests from multiple, different LMSs and
   it will understand the protocols of different RLMSs. It does not have any
   LMS dependent code, but it has RLMS dependent plug-ins.

#. The RLMS, which will support a federation protocol to process requests from
   the LabManager. The federation protocol from one RLMS to other will be
   different. It should not require anything special for being supported by the
   LabManager.

This way, if a new LMS is aimed, a new plug-in for that LMS is required in the
LMS, but it has no impact on the rest of the RLMSs neither on the LabManager. If
a new RLMS is aimed, a new plug-in for that RLMS is required in the LabManager,
but it has no impact on the LMSs.

Let's detail a typical scenario. *University A* uses Moodle (LMS), WebLab-Deusto
(RLMS 1) and MIT iLabs (RLMS 2). It will install a Moodle plug-in in the IT
services. THIS SECTION HAS NOT YET BEEN WRITTEN.

LMS to LabManager protocol
~~~~~~~~~~~~~~~~~~~~~~~~~~

THIS SECTION HAS NOT YET BEEN WRITTEN

Requests::

    POST /lms4labs/labmanager/requests/ HTTP/1.0
    Authorization: Basic ASDFASDF (usuario y clave del LMS)

    {
       "user-id"    : "elio",
       "full-name"  : "Elio Sancristobal",
       "is-admin"   : true, 
       "user-agent" : "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:12.0) Gecko/20100101 Firefox/12.0",
       "origin-ip"  : "192.168.1.1",
       "referer"    : "http://.../", 
       "courses"    : {
            "01"    : "s",
            "02"    : "s",
            "03"    : "t", // "t" = teacher, "s" = student
            "04"    : "s", 
       },
       "request-payload" : "SOMETHING-THAT-SCORM-SENDS"
    }

Authenticate::

    GET /lms4labs/lms/authenticate HTTP/1.0

    POST /lms4labs/labmanager/lms/admin/authenticate/ HTTP/1.0
    Authorization: Basic ASDFASDF (usuario y clave del LMS)

    {
        "full-name" : "Elio Sancristobal"
    }

Course listing::

    GET /lms4labs/lms/list?q=elect&start=0 HTTP/1.1
    Authorization: Basic ASDFASDF (usuario y clave del Lab Manager)

Response::

    {
       "start"    :   150,
       "number"   : 34000,
       "per-page" :    10,
       "courses" : [

             {
            "id"   : "3465", 
            "name" : "Arquitectura Computadores"
             },
         {
                    "id"   : "2854",
                    name"  : "Laboratorio de Electrónica"
             },
             {
            "id"   : "2854", 
            "name" : "IEEE Student Branch"
             },
       ],
    }

