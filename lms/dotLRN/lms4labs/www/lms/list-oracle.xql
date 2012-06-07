<?xml version="1.0"?>

<queryset>

    <fullquery name="get_courses_list">
      <querytext>
	select community_id as id
	       pretty_name as name
	from (
	    select community_id, pretty_name, row_number() over (order by community_id) rnk
	    from   dotlrn_communities_all
	    where  archived_p = 'f')
	where rnk between :start_num and :end_num
      </querytext>
    </fullquery>

</queryset>
