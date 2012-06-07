<?xml version="1.0"?>

<queryset>
    <rdbms><type>postgresql</type><version>7.1</version></rdbms>

    <fullquery name="check_swa">
      <querytext>
          select CASE WHEN grantee_id=:user_id THEN 'true' ELSE 'false' END
          from   acs_permissions
          where  object_id = -4
            and  privilege = 'admin'
            and  grantee_id = :user_id
      </querytext>
    </fullquery>

    <fullquery name="get_courses_list">
      <querytext>
        select dotlrn_communities_all.community_id,
               CASE WHEN dotlrn_member_rels_approved.role in ('admin','course_admin','instructor','teaching_assistant','course_assistant') THEN 't' ELSE 's' END
        from   dotlrn_communities_all,
               dotlrn_member_rels_approved
        where  dotlrn_communities_all.community_id = dotlrn_member_rels_approved.community_id
          and  dotlrn_member_rels_approved.user_id = :user_id
        order by dotlrn_communities_all.tree_sortkey
      </querytext>
    </fullquery>

</queryset>
