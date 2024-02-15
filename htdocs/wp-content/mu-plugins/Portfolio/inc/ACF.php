<?php
/**
 * This file is part of the AmphiBee package.
 * (c) AmphiBee <contact@amhibee.fr>
 */


namespace Portfolio;

use Geniem\ACF\ConditionalLogicGroup;
use Geniem\ACF\Exception;
use Geniem\ACF\Field\Select;
use Geniem\ACF\Field\Text;
use Geniem\ACF\Group;
use Geniem\ACF\RuleGroup;

class ACF
{
    /**
     * @throws Exception
     */
    public function register(): void
    {
        $field_group = new Group("Codifier fields");
        $field_group
            ->set_key('codifier_fields')
            ->register();

        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType::SLUG );

        $field_group->add_rule_group( $rule_group );

        $text_field = new Text("URL du projet", 'url-projet');
        $field_group->add_field($text_field);

        $area_field = new Select("Secteur d'activité", 'area', 'area');
        $area_field->set_choices([
            'none' => 'Choisir secteur',
            'agency' => 'Agence',
            'retail' => 'Commerce',
            'communication' => 'Communication',
            'cosmetics' => 'Cosmétiques',
            'marketing' => 'Marketing',
        ]);
        $field_group->add_field($area_field);
    }
}