<?xml version="1.0"?>

<queryset>
    <rdbms><type>postgresql</type><version>7.1</version></rdbms>

    <fullquery name="get_user_name">
      <querytext>
          select first_names || ' ' || last_name
          from   persons
          where  person_id = :user_id
      </querytext>
    </fullquery>

</queryset>
