<?xml version="1.0"?>

<queryset>

    <fullquery name="get_courses_list">
      <querytext>
	select community_id as id, pretty_name as name
	from   dotlrn_communities_all
        where  archived_p = 'f'
	  $search_clause
	LIMIT :num_results OFFSET :start_num
      </querytext>
    </fullquery>

</queryset>
