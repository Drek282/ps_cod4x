<?php
/**
 *	This file is part of PsychoStats.
 *
 *	Written by Jason Morriss
 *	Copyright 2008 Jason Morriss
 *
 *	PsychoStats is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	PsychoStats is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with PsychoStats.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	Version: $Id: cod4x.php 498 2008-06-19 16:12:15Z lifo $
 *
 *	PS::cod4x class for Call of Duty game support
 *	
 */
if (!defined("PSYCHOSTATS_PAGE")) die("Unauthorized access to " . basename(__FILE__));
if (defined("CLASS_PS_COD4X_PHP")) return 1;
define("CLASS_PS_COD4X_PHP", 1);

include_once(__DIR__ . '/PS.php');

class PS_cod4x extends PS {

var $class = 'PS::cod4x';

var $CLAN_MODTYPES = array(
	'coalitionkills'		=> '+',
	'coalitionkillspct'	=> array( 'percent2', 'coalitionkills', 'allegiancekills' ),
	'allegiancekills'		=> '+',
	'allegiancekillspct'		=> array( 'percent2', 'allegiancekills', 'coalitionkills' ),
	'coalitiondeaths'		=> '+',
	'allegiancedeaths'		=> '+',
	'joinedcoalition'		=> '+',
	'joinedallegiance'		=> '+',
	'coalitionwon'		=> '+',
	'coalitionwonpct'		=> array( 'percent2', 'coalitionwon', 'allegiancewon' ),
	'allegiancewon'		=> '+',
	'allegiancewonpct'		=> array( 'percent2', 'allegiancewon', 'coalitionwon' ),
	'coalitionlost'		=> '+',
	'allegiancelost'		=> '+',
);


function PS_cod4x(&$db) {
	parent::PS($db);
	$this->CLAN_MAP_MODTYPES = $this->CLAN_MODTYPES;
	$this->cod4x_remove_columns = array('headshotkills','headshotkillspct','accuracy');
}

function worldid_noun($plural = false) {
	global $cms;
	return $plural ? $cms->trans('GUIDs') : $cms->trans('GUID');
}

function add_map_player_list_mod($map, $setup = array()) {
	global $cms;
	$this->add_map_player_list('touchedhostages', $setup + array('label' => $cms->trans("Most Hostages Touched")) );
}

function index_table_mod(&$table) {
	global $cms;
	$table->remove_columns($this->cod4x_remove_columns);
	$table->insert_columns(
		array( 
			'flagscaptured' => array( 'label' => $cms->trans('Flags'), 'tooltip' => $cms->trans("Flags captured") ), 
		),
		'onlinetime',
		false
	);
}

function maps_table_mod(&$table) {
	global $cms;
	$table->insert_columns(
		array( 
			'allegiancekillspct' => array( 'label' => $cms->trans('Team Kills'), 'tooltip' => $cms->trans("Allegiance / Coalition Kills"), 'callback' => array(&$this, 'team_wins') ), 
		),
		'rounds',
		true
	);
	$table->remove_columns('rounds');
}

function weapons_table_mod(&$table) {
	$table->remove_columns($this->cod4x_remove_columns);
}
function weapon_players_table_mod(&$table) {
	$table->remove_columns($this->cod4x_remove_columns);
}

function clans_table_mod(&$table) {
	$table->remove_columns($this->cod4x_remove_columns);
}

function clan_weapons_table_mod(&$table) {
	$table->remove_columns($this->cod4x_remove_columns);
}
function clan_players_table_mod(&$table) {
	$table->remove_columns($this->cod4x_remove_columns);
}

function player_sessions_table_mod(&$table) {
	$table->remove_columns($this->cod4x_remove_columns);
}
function player_weapons_table_mod(&$table) {
	$table->remove_columns($this->cod4x_remove_columns);
}


function map_left_column_mod(&$map, &$theme) {
	// maps and players have the same stats ...
	$this->player_left_column_mod($map, $theme);
	$theme->assign('map_left_column_mod', $theme->get_template_vars('player_left_column_mod'));
}

function clan_left_column_mod(&$clan, &$theme) {
	// clans and players have the same stats ...
	$this->player_left_column_mod($clan, $theme);
	$theme->assign('clan_left_column_mod', $theme->get_template_vars('player_left_column_mod'));
}

function player_left_column_mod(&$plr, &$theme) {
	global $cms;
	$tpl = 'player_left_column_mod';
	if ($theme->template_found($tpl, false)) {
		$actions = array();
		$joined = $plr['joinedallegiance'] + $plr['joinedcoalition'];
		if ($joined) {
			$pct1 = sprintf('%0.02f', $plr['joinedallegiance'] / $joined * 100);
			$pct2 = sprintf('%0.02f', $plr['joinedcoalition'] / $joined * 100);
		} else {
			$pct1 = $pct2 = 0;
		}

		$actions['kills'] = array(
			'label'	=> $cms->trans("Allegiance / Coalition Kills"),
			'type'	=> 'dual_bar',
			'value'	=> array(
				'pct1'	 	=> $plr['allegiancekillspct'],
				'pct2'	 	=> $plr['coalitionkillspct'],
				'title1'	=> commify($plr['allegiancekills']) . ' ' . $cms->trans('allegiance') . ' (' . $plr['allegiancekillspct'] . '%)',
				'title2'	=> commify($plr['coalitionkills']) . ' ' . $cms->trans('ally') . ' (' . $plr['coalitionkillspct'] . '%)',
				'width'		=> 130
			)
		);

		$cms->filter('left_column_actions', $actions);
		foreach (array_keys($actions) as $i) {
			if ($actions[$i]['type'] == 'dual_bar') {
				$actions[$i]['value'] = dual_bar( $actions[$i]['value'] );
			} else {
				$actions[$i]['value'] = pct_bar( $actions[$i]['value'] );
			}
		}
		
		$theme->assign(array(
			'mod_actions' => $actions,
			'mod_actions_title' => $cms->trans("Team / Action Profile"),
		));
		$output = $theme->parse($tpl);
		$theme->assign('player_left_column_mod', $output);
	}
}

// used in maps.php as a table callback for the wins of each team
function team_wins($value, $data) {
	global $cms;
	$bar = dual_bar(array(
		'pct1'	=> $data['allegiancekillspct'], 
		'pct2'	=> $data['coalitionkillspct'],
		'title1'=> commify($data['allegiancekills']) . " " . $cms->trans("Allegiance Kills") . " (" . $data['allegiancekillspct'] . "%)",
		'title2'=> commify($data['coalitionkills']) . " " . $cms->trans("Coalition Kills") . " (" . $data['coalitionkillspct'] . "%)",
	));
	return $bar;
}

} // END of PS::cod4x

?>
