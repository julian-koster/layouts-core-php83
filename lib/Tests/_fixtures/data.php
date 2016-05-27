<?php

return array(
    'ngbm_layout' => array(
        array('id' => 1, 'status' => 0, 'parent_id' => null, 'type' => '3_zones_a', 'name' => 'My layout', 'created' => 1447065813, 'modified' => 1447065813),
        array('id' => 1, 'status' => 1, 'parent_id' => null, 'type' => '3_zones_a', 'name' => 'My layout', 'created' => 1447065813, 'modified' => 1447065813),
        array('id' => 2, 'status' => 0, 'parent_id' => null, 'type' => '3_zones_b', 'name' => 'My other layout', 'created' => 1447065813, 'modified' => 1447065813),
        array('id' => 2, 'status' => 1, 'parent_id' => null, 'type' => '3_zones_b', 'name' => 'My other layout', 'created' => 1447065813, 'modified' => 1447065813),
    ),
    'ngbm_zone' => array(
        array('identifier' => 'top_left', 'layout_id' => 1, 'status' => 0),
        array('identifier' => 'top_left', 'layout_id' => 1, 'status' => 1),
        array('identifier' => 'top_right', 'layout_id' => 1, 'status' => 0),
        array('identifier' => 'top_right', 'layout_id' => 1, 'status' => 1),
        array('identifier' => 'bottom', 'layout_id' => 1, 'status' => 0),
        array('identifier' => 'bottom', 'layout_id' => 1, 'status' => 1),
        array('identifier' => 'top', 'layout_id' => 2, 'status' => 0),
        array('identifier' => 'top', 'layout_id' => 2, 'status' => 1),
        array('identifier' => 'bottom_left', 'layout_id' => 2, 'status' => 0),
        array('identifier' => 'bottom_left', 'layout_id' => 2, 'status' => 1),
        array('identifier' => 'bottom_right', 'layout_id' => 2, 'status' => 0),
        array('identifier' => 'bottom_right', 'layout_id' => 2, 'status' => 1),
    ),
    'ngbm_block' => array(
        array('id' => 1, 'status' => 0, 'layout_id' => 1, 'zone_identifier' => 'top_right', 'position' => 0, 'definition_identifier' => 'paragraph', 'view_type' => 'default', 'name' => 'My block', 'parameters' => '{"some_param": "some_value"}'),
        array('id' => 1, 'status' => 1, 'layout_id' => 1, 'zone_identifier' => 'top_right', 'position' => 0, 'definition_identifier' => 'paragraph', 'view_type' => 'default', 'name' => 'My block', 'parameters' => '{"some_param": "some_value"}'),
        array('id' => 2, 'status' => 0, 'layout_id' => 1, 'zone_identifier' => 'top_right', 'position' => 1, 'definition_identifier' => 'title', 'view_type' => 'small', 'name' => 'My other block', 'parameters' => '{"other_param": "other_value"}'),
        array('id' => 2, 'status' => 1, 'layout_id' => 1, 'zone_identifier' => 'top_right', 'position' => 1, 'definition_identifier' => 'title', 'view_type' => 'small', 'name' => 'My other block', 'parameters' => '{"other_param": "other_value"}'),
        array('id' => 3, 'status' => 0, 'layout_id' => 2, 'zone_identifier' => 'bottom_right', 'position' => 0, 'definition_identifier' => 'paragraph', 'view_type' => 'large', 'name' => 'My third block', 'parameters' => '{"test_param": "test_value"}'),
        array('id' => 3, 'status' => 1, 'layout_id' => 2, 'zone_identifier' => 'bottom_right', 'position' => 0, 'definition_identifier' => 'paragraph', 'view_type' => 'large', 'name' => 'My third block', 'parameters' => '{"test_param": "test_value"}'),
        array('id' => 4, 'status' => 0, 'layout_id' => 2, 'zone_identifier' => 'bottom_right', 'position' => 1, 'definition_identifier' => 'title', 'view_type' => 'small', 'name' => 'My fourth block', 'parameters' => '{"the_answer": 42}'),
        array('id' => 4, 'status' => 1, 'layout_id' => 2, 'zone_identifier' => 'bottom_right', 'position' => 1, 'definition_identifier' => 'title', 'view_type' => 'small', 'name' => 'My fourth block', 'parameters' => '{"the_answer": 42}'),
        array('id' => 5, 'status' => 0, 'layout_id' => 1, 'zone_identifier' => 'top_right', 'position' => 2, 'definition_identifier' => 'title', 'view_type' => 'small', 'name' => 'My fourth block', 'parameters' => '{"the_answer": 42}'),
        array('id' => 5, 'status' => 1, 'layout_id' => 1, 'zone_identifier' => 'top_right', 'position' => 2, 'definition_identifier' => 'title', 'view_type' => 'small', 'name' => 'My fourth block', 'parameters' => '{"the_answer": 42}'),
    ),
    'ngbm_collection' => array(
        array('id' => 1, 'status' => 0, 'type' => 0, 'name' => null),
        array('id' => 2, 'status' => 1, 'type' => 1, 'name' => null),
        array('id' => 3, 'status' => 0, 'type' => 2, 'name' => 'My collection'),
        array('id' => 3, 'status' => 1, 'type' => 2, 'name' => 'My collection'),
    ),
    'ngbm_collection_item' => array(
        array('id' => 1, 'status' => 0, 'collection_id' => 1, 'position' => 0, 'type' => 0, 'value_id' => '70', 'value_type' => 'ezcontent'),
        array('id' => 2, 'status' => 0, 'collection_id' => 1, 'position' => 1, 'type' => 0, 'value_id' => '71', 'value_type' => 'ezcontent'),
        array('id' => 3, 'status' => 0, 'collection_id' => 1, 'position' => 2, 'type' => 0, 'value_id' => '72', 'value_type' => 'ezcontent'),
        array('id' => 4, 'status' => 1, 'collection_id' => 2, 'position' => 1, 'type' => 0, 'value_id' => '70', 'value_type' => 'ezcontent'),
        array('id' => 5, 'status' => 1, 'collection_id' => 2, 'position' => 2, 'type' => 0, 'value_id' => '71', 'value_type' => 'ezcontent'),
        array('id' => 6, 'status' => 1, 'collection_id' => 2, 'position' => 5, 'type' => 1, 'value_id' => '72', 'value_type' => 'ezcontent'),
        array('id' => 7, 'status' => 0, 'collection_id' => 3, 'position' => 2, 'type' => 0, 'value_id' => '70', 'value_type' => 'ezcontent'),
        array('id' => 7, 'status' => 1, 'collection_id' => 3, 'position' => 2, 'type' => 0, 'value_id' => '70', 'value_type' => 'ezcontent'),
        array('id' => 8, 'status' => 0, 'collection_id' => 3, 'position' => 3, 'type' => 0, 'value_id' => '71', 'value_type' => 'ezcontent'),
        array('id' => 8, 'status' => 1, 'collection_id' => 3, 'position' => 3, 'type' => 0, 'value_id' => '71', 'value_type' => 'ezcontent'),
        array('id' => 9, 'status' => 0, 'collection_id' => 3, 'position' => 5, 'type' => 0, 'value_id' => '72', 'value_type' => 'ezcontent'),
        array('id' => 9, 'status' => 1, 'collection_id' => 3, 'position' => 5, 'type' => 0, 'value_id' => '72', 'value_type' => 'ezcontent'),
        array('id' => 10, 'status' => 0, 'collection_id' => 3, 'position' => 7, 'type' => 1, 'value_id' => '154', 'value_type' => 'ezcontent'),
        array('id' => 10, 'status' => 1, 'collection_id' => 3, 'position' => 7, 'type' => 1, 'value_id' => '154', 'value_type' => 'ezcontent'),
        array('id' => 11, 'status' => 0, 'collection_id' => 3, 'position' => 8, 'type' => 1, 'value_id' => '155', 'value_type' => 'ezcontent'),
        array('id' => 11, 'status' => 1, 'collection_id' => 3, 'position' => 8, 'type' => 1, 'value_id' => '155', 'value_type' => 'ezcontent'),
    ),
    'ngbm_collection_query' => array(
        array('id' => 1, 'status' => 1, 'collection_id' => 2, 'position' => 0, 'identifier' => 'default', 'type' => 'ezcontent_search', 'parameters' => '{"param": "value"}'),
        array('id' => 2, 'status' => 0, 'collection_id' => 3, 'position' => 0, 'identifier' => 'default', 'type' => 'ezcontent_search', 'parameters' => '{"param": "value"}'),
        array('id' => 2, 'status' => 1, 'collection_id' => 3, 'position' => 0, 'identifier' => 'default', 'type' => 'ezcontent_search', 'parameters' => '{"param": "value"}'),
        array('id' => 3, 'status' => 0, 'collection_id' => 3, 'position' => 1, 'identifier' => 'featured', 'type' => 'ezcontent_search', 'parameters' => '{"param": "value"}'),
        array('id' => 3, 'status' => 1, 'collection_id' => 3, 'position' => 1, 'identifier' => 'featured', 'type' => 'ezcontent_search', 'parameters' => '{"param": "value"}'),
    ),
    'ngbm_block_collection' => array(
        array('block_id' => 1, 'block_status' => 0, 'collection_id' => 1, 'collection_status' => 0, 'identifier' => 'default', 'start' => 0, 'length' => null),
        array('block_id' => 1, 'block_status' => 0, 'collection_id' => 3, 'collection_status' => 1, 'identifier' => 'featured', 'start' => 0, 'length' => null),
        array('block_id' => 1, 'block_status' => 1, 'collection_id' => 2, 'collection_status' => 1, 'identifier' => 'default', 'start' => 0, 'length' => null),
        array('block_id' => 1, 'block_status' => 1, 'collection_id' => 3, 'collection_status' => 1, 'identifier' => 'featured', 'start' => 0, 'length' => null),
        array('block_id' => 2, 'block_status' => 0, 'collection_id' => 3, 'collection_status' => 1, 'identifier' => 'default', 'start' => 0, 'length' => null),
        array('block_id' => 2, 'block_status' => 1, 'collection_id' => 3, 'collection_status' => 1, 'identifier' => 'default', 'start' => 0, 'length' => null),
    ),
    'ngbm_rule' => array(
        array('id' => 1, 'status' => 1, 'layout_id' => 1, 'priority' => 0, 'comment' => 'My comment'),
        array('id' => 2, 'status' => 1, 'layout_id' => 2, 'priority' => 1, 'comment' => 'My other comment'),
        array('id' => 3, 'status' => 1, 'layout_id' => 3, 'priority' => 2, 'comment' => null),
        array('id' => 4, 'status' => 1, 'layout_id' => 1, 'priority' => 3, 'comment' => null),
        array('id' => 5, 'status' => 0, 'layout_id' => 2, 'priority' => 4, 'comment' => null),
        array('id' => 5, 'status' => 1, 'layout_id' => 2, 'priority' => 4, 'comment' => null),
        array('id' => 6, 'status' => 1, 'layout_id' => 3, 'priority' => 5, 'comment' => null),
        array('id' => 7, 'status' => 0, 'layout_id' => 4, 'priority' => 6, 'comment' => null),
        array('id' => 7, 'status' => 1, 'layout_id' => 4, 'priority' => 6, 'comment' => null),
        array('id' => 8, 'status' => 1, 'layout_id' => 5, 'priority' => 7, 'comment' => null),
        array('id' => 9, 'status' => 1, 'layout_id' => 6, 'priority' => 8, 'comment' => null),
        array('id' => 10, 'status' => 1, 'layout_id' => 7, 'priority' => 9, 'comment' => null),
        array('id' => 11, 'status' => 1, 'layout_id' => 11, 'priority' => 10, 'comment' => null),
        array('id' => 12, 'status' => 1, 'layout_id' => 12, 'priority' => 11, 'comment' => null),
        array('id' => 13, 'status' => 1, 'layout_id' => 13, 'priority' => 12, 'comment' => null),
        array('id' => 14, 'status' => 1, 'layout_id' => 14, 'priority' => 13, 'comment' => null),
        array('id' => 15, 'status' => 1, 'layout_id' => null, 'priority' => 14, 'comment' => null),
        array('id' => 16, 'status' => 1, 'layout_id' => 16, 'priority' => 15, 'comment' => null),
        array('id' => 17, 'status' => 1, 'layout_id' => 17, 'priority' => 16, 'comment' => null),
        array('id' => 18, 'status' => 1, 'layout_id' => 18, 'priority' => 17, 'comment' => null),
        array('id' => 19, 'status' => 1, 'layout_id' => 19, 'priority' => 18, 'comment' => null),
        array('id' => 20, 'status' => 1, 'layout_id' => 20, 'priority' => 19, 'comment' => null),
        array('id' => 21, 'status' => 1, 'layout_id' => 21, 'priority' => 20, 'comment' => null),
    ),
    'ngbm_rule_data' => array(
        array('rule_id' => 1, 'enabled' => 1),
        array('rule_id' => 2, 'enabled' => 1),
        array('rule_id' => 3, 'enabled' => 1),
        array('rule_id' => 4, 'enabled' => 0),
        array('rule_id' => 5, 'enabled' => 0),
        array('rule_id' => 6, 'enabled' => 1),
        array('rule_id' => 7, 'enabled' => 1),
        array('rule_id' => 8, 'enabled' => 1),
        array('rule_id' => 9, 'enabled' => 1),
        array('rule_id' => 10, 'enabled' => 1),
        array('rule_id' => 11, 'enabled' => 1),
        array('rule_id' => 12, 'enabled' => 1),
        array('rule_id' => 13, 'enabled' => 1),
        array('rule_id' => 14, 'enabled' => 1),
        array('rule_id' => 15, 'enabled' => 0),
        array('rule_id' => 16, 'enabled' => 0),
        array('rule_id' => 17, 'enabled' => 1),
        array('rule_id' => 18, 'enabled' => 1),
        array('rule_id' => 19, 'enabled' => 1),
        array('rule_id' => 20, 'enabled' => 1),
        array('rule_id' => 21, 'enabled' => 1),
    ),
    'ngbm_rule_target' => array(
        array('id' => 1, 'status' => 1, 'rule_id' => 1, 'identifier' => 'route', 'value' => 'my_cool_route'),
        array('id' => 2, 'status' => 1, 'rule_id' => 1, 'identifier' => 'route', 'value' => 'my_other_cool_route'),
        array('id' => 3, 'status' => 1, 'rule_id' => 2, 'identifier' => 'route', 'value' => 'my_second_cool_route'),
        array('id' => 4, 'status' => 1, 'rule_id' => 2, 'identifier' => 'route', 'value' => 'my_third_cool_route'),
        array('id' => 5, 'status' => 1, 'rule_id' => 3, 'identifier' => 'route', 'value' => 'my_fourth_cool_route'),
        array('id' => 6, 'status' => 1, 'rule_id' => 3, 'identifier' => 'route', 'value' => 'my_fifth_cool_route'),
        array('id' => 7, 'status' => 1, 'rule_id' => 4, 'identifier' => 'route_prefix', 'value' => 'my_cool_'),
        array('id' => 8, 'status' => 1, 'rule_id' => 4, 'identifier' => 'route_prefix', 'value' => 'my_other_cool_'),
        array('id' => 9, 'status' => 0, 'rule_id' => 5, 'identifier' => 'route_prefix', 'value' => 'my_second_cool_'),
        array('id' => 9, 'status' => 1, 'rule_id' => 5, 'identifier' => 'route_prefix', 'value' => 'my_second_cool_'),
        array('id' => 10, 'status' => 0, 'rule_id' => 5, 'identifier' => 'route_prefix', 'value' => 'my_third_cool_'),
        array('id' => 10, 'status' => 1, 'rule_id' => 5, 'identifier' => 'route_prefix', 'value' => 'my_third_cool_'),
        array('id' => 11, 'status' => 1, 'rule_id' => 6, 'identifier' => 'route_prefix', 'value' => 'my_fourth_cool_'),
        array('id' => 12, 'status' => 1, 'rule_id' => 6, 'identifier' => 'route_prefix', 'value' => 'my_fifth_cool_'),
        array('id' => 13, 'status' => 0, 'rule_id' => 7, 'identifier' => 'path_info', 'value' => '/the/answer'),
        array('id' => 13, 'status' => 1, 'rule_id' => 7, 'identifier' => 'path_info', 'value' => '/the/answer'),
        array('id' => 14, 'status' => 0, 'rule_id' => 7, 'identifier' => 'path_info', 'value' => '/the/other/answer'),
        array('id' => 14, 'status' => 1, 'rule_id' => 7, 'identifier' => 'path_info', 'value' => '/the/other/answer'),
        array('id' => 15, 'status' => 1, 'rule_id' => 8, 'identifier' => 'path_info_prefix', 'value' => '/the/'),
        array('id' => 16, 'status' => 1, 'rule_id' => 8, 'identifier' => 'path_info_prefix', 'value' => '/a/'),
        array('id' => 17, 'status' => 1, 'rule_id' => 9, 'identifier' => 'request_uri', 'value' => '/the/answer?a=42'),
        array('id' => 18, 'status' => 1, 'rule_id' => 9, 'identifier' => 'request_uri', 'value' => '/the/answer?a=43'),
        array('id' => 19, 'status' => 1, 'rule_id' => 10, 'identifier' => 'request_uri_prefix', 'value' => '/the/'),
        array('id' => 20, 'status' => 1, 'rule_id' => 10, 'identifier' => 'request_uri_prefix', 'value' => '/a/'),
        array('id' => 21, 'status' => 1, 'rule_id' => 11, 'identifier' => 'location', 'value' => 42),
        array('id' => 22, 'status' => 1, 'rule_id' => 11, 'identifier' => 'location', 'value' => 43),
        array('id' => 23, 'status' => 1, 'rule_id' => 12, 'identifier' => 'location', 'value' => 44),
        array('id' => 24, 'status' => 1, 'rule_id' => 12, 'identifier' => 'location', 'value' => 45),
        array('id' => 25, 'status' => 1, 'rule_id' => 13, 'identifier' => 'location', 'value' => 46),
        array('id' => 26, 'status' => 1, 'rule_id' => 13, 'identifier' => 'location', 'value' => 47),
        array('id' => 27, 'status' => 1, 'rule_id' => 14, 'identifier' => 'content', 'value' => 48),
        array('id' => 28, 'status' => 1, 'rule_id' => 14, 'identifier' => 'content', 'value' => 49),
        array('id' => 29, 'status' => 1, 'rule_id' => 15, 'identifier' => 'content', 'value' => 50),
        array('id' => 30, 'status' => 1, 'rule_id' => 15, 'identifier' => 'content', 'value' => 51),
        array('id' => 33, 'status' => 1, 'rule_id' => 17, 'identifier' => 'children', 'value' => 54),
        array('id' => 34, 'status' => 1, 'rule_id' => 17, 'identifier' => 'children', 'value' => 55),
        array('id' => 35, 'status' => 1, 'rule_id' => 18, 'identifier' => 'subtree', 'value' => 2),
        array('id' => 36, 'status' => 1, 'rule_id' => 18, 'identifier' => 'subtree', 'value' => 3),
        array('id' => 37, 'status' => 1, 'rule_id' => 19, 'identifier' => 'subtree', 'value' => 4),
        array('id' => 38, 'status' => 1, 'rule_id' => 19, 'identifier' => 'subtree', 'value' => 5),
        array('id' => 39, 'status' => 1, 'rule_id' => 20, 'identifier' => 'semantic_path_info', 'value' => '/the/answer'),
        array('id' => 40, 'status' => 1, 'rule_id' => 20, 'identifier' => 'semantic_path_info', 'value' => '/the/other/answer'),
        array('id' => 41, 'status' => 1, 'rule_id' => 21, 'identifier' => 'semantic_path_info_prefix', 'value' => '/the/'),
        array('id' => 42, 'status' => 1, 'rule_id' => 21, 'identifier' => 'semantic_path_info_prefix', 'value' => '/a/'),
    ),
    'ngbm_rule_condition' => array(
        array('id' => 1, 'status' => 1, 'rule_id' => 2, 'identifier' => 'route_parameter', 'value' => '{"some_param": [1,2]}'),
        array('id' => 2, 'status' => 1, 'rule_id' => 3, 'identifier' => 'route_parameter', 'value' => '{"some_param": [3,4]}'),
        array('id' => 3, 'status' => 1, 'rule_id' => 3, 'identifier' => 'route_parameter', 'value' => '{"some_other_param": [5,6]}'),
        array('id' => 4, 'status' => 0, 'rule_id' => 5, 'identifier' => 'siteaccess', 'value' => '["cro"]'),
        array('id' => 4, 'status' => 1, 'rule_id' => 5, 'identifier' => 'siteaccess', 'value' => '["cro"]'),
    ),
);
