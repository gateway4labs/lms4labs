<?xml version="1.0"?>

<queryset>

  <fullquery name="select_pretty_name">
    <querytext>
      select pretty_name as instance_name
      from   apm_package_types
      where  package_key = :package_key
    </querytext>
  </fullquery>

  <fullquery name="select_instance_name">
    <querytext>
      select instance_name, package_key
      from   apm_packages
      where  package_id = :package_id
    </querytext>
  </fullquery>

  <fullquery name="select_params">      
    <querytext>
      select p.parameter_name,
        coalesce(p.description, 'No Description') as description,
        v.attr_value,
        coalesce(p.section_name, '') as section_name,
        p.datatype
      from apm_parameters p left outer join
        (select v.parameter_id, v.attr_value
         from apm_parameter_values v
         where (v.package_id = :package_id or v.package_id is null)) v
        on p.parameter_id = v.parameter_id
      where p.package_key = :package_key
        and p.scope = :scope
      order  by section_name, parameter_name
    </querytext>
  </fullquery>

  <fullquery name="get_courses">      
    <querytext>
      select community_id, pretty_name, description, '' as area, dotlrn_community__url(community_id) as community_url
      from   dotlrn_communities_all
    </querytext>
  </fullquery>

</queryset>
