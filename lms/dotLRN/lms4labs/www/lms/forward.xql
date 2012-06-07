<?xml version="1.0"?>

<queryset>

    <fullquery name="get_user_name">
      <querytext>
          select first_names || ' ' || last_name
          from   persons
          where  person_id = :user_id
      </querytext>
    </fullquery>

    <fullquery name="get_courses_list">
      <querytext>
        select dotlrn_communities_all.community_id,
               dotlrn_member_rels_approved.role
        from   dotlrn_communities_all,
               dotlrn_member_rels_approved
        where  dotlrn_communities_all.community_id = dotlrn_member_rels_approved.community_id
          and  dotlrn_member_rels_approved.user_id = :user_id
        order by dotlrn_communities_all.tree_sortkey
      </querytext>
    </fullquery>

</queryset>
