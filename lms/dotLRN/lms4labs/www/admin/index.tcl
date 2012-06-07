ad_page_contract {

	Management of LMS dotLRN for LMS4LABS integration enviroment

	@author Alberto Pesquera Martin (apm@innova.uned.es)
	@creation-date 31 May 2012

} {
    {scope "instance"}
    {return_url "[ad_conn url]"}
}

set package_id [ad_conn package_id]

db_1row select_instance_name {}

set package_url [site_node::get_url_from_object_id -object_id $package_id]
set page_title "$instance_name Instance Parameters"
set context [list [list $package_url $instance_name] [list "${package_url}admin/" "Administration"] $page_title]

db_multirow params select_params {} {}
db_multirow courses get_courses {} {}

set param_actions [list "Edit parameters" [export_vars -base "/shared/parameters" [list package_id return_url] ] "Edit parameters for $instance_name"]
set courses_actions [list "Associate courses" [export_vars -base "" ] "Associate courses for $instance_name"]
set courses_bulk_actions [list \
			      "Drop course" "" "Drop this course for $instance_name" \
    ]

template::list::create \
    -name params \
    -no_data "No data found" \
    -actions $param_actions \
    -elements {
	parameter_name {
	    label "Parameter name"
	}
	description {
	    label "Description"
	}
	attr_value {
	    label "Value"
	}
	section_name {
	    label "Section name"
	}
	datatype {
	    label "Data type"
	}
    }

template::list::create \
    -name courses \
    -key community_id \
    -no_data "No data found" \
    -actions $courses_actions \
    -bulk_actions $courses_bulk_actions \
    -elements {
	pretty_name {
	    label "Course name"
	    display_template {
		<a href="@courses.community_url@">@courses.pretty_name@</a>
	    }
	}
	description {
	    label "Description"
	}
	area {
	    label "Area"
	}
    }
