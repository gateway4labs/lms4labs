<?xml version="1.0"?>

<queryset>

    <fullquery name="get_courses_list_old">
      <querytext>
        select community_id as id,
               pretty_name as name
        from   dotlrn_communities_all
        where  archived_p = 'f'
        order by dotlrn_communities_all.tree_sortkey
      </querytext>
    </fullquery>

    <fullquery name="get_total_results">
      <querytext>
        select count(distinct community_id) as total_results
        from   dotlrn_communities_all
        where  archived_p = 'f'
	  $search_clause
      </querytext>
    </fullquery>

    <partialquery name="with_search_clause">
      <querytext>
	and  pretty_name like :q2        
      </querytext>
    </partialquery>	      

    <partialquery name="without_search_clause">
      <querytext>
	  and  1 = 1
      </querytext>
    </partialquery>	      

</queryset>
