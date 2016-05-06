<?php

return array(
    'ngbm_rule' => array(
        array('id' => 1, 'layout_id' => 1, 'target_identifier' => 'route'),
        array('id' => 2, 'layout_id' => 2, 'target_identifier' => 'route'),
        array('id' => 3, 'layout_id' => 3, 'target_identifier' => 'route'),
        array('id' => 4, 'layout_id' => 1, 'target_identifier' => 'route_prefix'),
        array('id' => 5, 'layout_id' => 2, 'target_identifier' => 'route_prefix'),
        array('id' => 6, 'layout_id' => 3, 'target_identifier' => 'route_prefix'),
        array('id' => 7, 'layout_id' => 4, 'target_identifier' => 'path_info'),
        array('id' => 8, 'layout_id' => 5, 'target_identifier' => 'path_info_prefix'),
        array('id' => 9, 'layout_id' => 6, 'target_identifier' => 'request_uri'),
        array('id' => 10, 'layout_id' => 7, 'target_identifier' => 'request_uri_prefix'),
        array('id' => 11, 'layout_id' => 11, 'target_identifier' => 'location'),
        array('id' => 12, 'layout_id' => 12, 'target_identifier' => 'location'),
        array('id' => 13, 'layout_id' => 13, 'target_identifier' => 'location'),
        array('id' => 14, 'layout_id' => 14, 'target_identifier' => 'content'),
        array('id' => 15, 'layout_id' => 15, 'target_identifier' => 'content'),
        array('id' => 16, 'layout_id' => 16, 'target_identifier' => 'children'),
        array('id' => 17, 'layout_id' => 17, 'target_identifier' => 'children'),
        array('id' => 18, 'layout_id' => 18, 'target_identifier' => 'subtree'),
        array('id' => 19, 'layout_id' => 19, 'target_identifier' => 'subtree'),
        array('id' => 20, 'layout_id' => 20, 'target_identifier' => 'semantic_path_info'),
        array('id' => 21, 'layout_id' => 21, 'target_identifier' => 'semantic_path_info_prefix'),
    ),
    'ngbm_rule_value' => array(
        array('id' => 1, 'rule_id' => 1, 'value' => 'my_cool_route'),
        array('id' => 2, 'rule_id' => 1, 'value' => 'my_other_cool_route'),
        array('id' => 3, 'rule_id' => 2, 'value' => 'my_second_cool_route'),
        array('id' => 4, 'rule_id' => 2, 'value' => 'my_third_cool_route'),
        array('id' => 5, 'rule_id' => 3, 'value' => 'my_fourth_cool_route'),
        array('id' => 6, 'rule_id' => 3, 'value' => 'my_fifth_cool_route'),
        array('id' => 7, 'rule_id' => 4, 'value' => 'my_cool_'),
        array('id' => 8, 'rule_id' => 4, 'value' => 'my_other_cool_'),
        array('id' => 9, 'rule_id' => 5, 'value' => 'my_second_cool_'),
        array('id' => 10, 'rule_id' => 5, 'value' => 'my_third_cool_'),
        array('id' => 11, 'rule_id' => 6, 'value' => 'my_fourth_cool_'),
        array('id' => 12, 'rule_id' => 6, 'value' => 'my_fifth_cool_'),
        array('id' => 13, 'rule_id' => 7, 'value' => '/the/answer'),
        array('id' => 14, 'rule_id' => 7, 'value' => '/the/other/answer'),
        array('id' => 15, 'rule_id' => 8, 'value' => '/the/'),
        array('id' => 16, 'rule_id' => 8, 'value' => '/a/'),
        array('id' => 17, 'rule_id' => 9, 'value' => '/the/answer?a=42'),
        array('id' => 18, 'rule_id' => 9, 'value' => '/the/answer?a=43'),
        array('id' => 19, 'rule_id' => 10, 'value' => '/the/'),
        array('id' => 20, 'rule_id' => 10, 'value' => '/a/'),
        array('id' => 21, 'rule_id' => 11, 'value' => 42),
        array('id' => 22, 'rule_id' => 11, 'value' => 43),
        array('id' => 23, 'rule_id' => 12, 'value' => 44),
        array('id' => 24, 'rule_id' => 12, 'value' => 45),
        array('id' => 25, 'rule_id' => 13, 'value' => 46),
        array('id' => 26, 'rule_id' => 13, 'value' => 47),
        array('id' => 27, 'rule_id' => 14, 'value' => 48),
        array('id' => 28, 'rule_id' => 14, 'value' => 49),
        array('id' => 29, 'rule_id' => 15, 'value' => 50),
        array('id' => 30, 'rule_id' => 15, 'value' => 51),
        array('id' => 31, 'rule_id' => 16, 'value' => 52),
        array('id' => 32, 'rule_id' => 16, 'value' => 53),
        array('id' => 33, 'rule_id' => 17, 'value' => 54),
        array('id' => 34, 'rule_id' => 17, 'value' => 55),
        array('id' => 35, 'rule_id' => 18, 'value' => 2),
        array('id' => 36, 'rule_id' => 18, 'value' => 3),
        array('id' => 37, 'rule_id' => 19, 'value' => 4),
        array('id' => 38, 'rule_id' => 19, 'value' => 5),
        array('id' => 39, 'rule_id' => 20, 'value' => '/the/answer'),
        array('id' => 40, 'rule_id' => 20, 'value' => '/the/other/answer'),
        array('id' => 41, 'rule_id' => 21, 'value' => '/the/'),
        array('id' => 42, 'rule_id' => 21, 'value' => '/a/'),
    ),
    'ngbm_rule_condition' => array(
        array('id' => 1, 'rule_id' => 2, 'identifier' => 'route_parameter', 'parameters' => '{"some_param": [1,2]}'),
        array('id' => 2, 'rule_id' => 3, 'identifier' => 'route_parameter', 'parameters' => '{"some_param": [3,4]}'),
        array('id' => 3, 'rule_id' => 3, 'identifier' => 'route_parameter', 'parameters' => '{"some_other_param": [5,6]}'),
    ),
);
