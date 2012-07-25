<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2012 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Julien Dombre
// Purpose of file:
// ----------------------------------------------------------------------

// Direct access to file
if (strpos($_SERVER['PHP_SELF'],"searchoptionvalue.php")) {
   define('GLPI_ROOT','..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkLoginUser();

/// TODO use standard getValueToSelect : need to have specific cases to have specific display passing options
if (isset($_REQUEST['searchtype'])) {
   $searchopt         = unserialize(stripslashes($_REQUEST['searchopt']));

   $_REQUEST['value'] = rawurldecode(stripslashes($_REQUEST['value']));

   $addmeta = "";
   if (isset($_REQUEST['meta']) && $_REQUEST['meta']) {
      $addmeta = '2';
   }

   $inputname = 'contains'.$addmeta.'['.$_REQUEST['num'].']';
   $display   = false;
   $item = getItemForItemtype($_REQUEST['itemtype']);
//             print_r($searchopt);

   switch ($_REQUEST['searchtype']) {
      case "equals" :
      case "notequals" :
      case "morethan" :
      case "lessthan" :
      case "under" :
      case "notunder" :
        if (!$display && isset($searchopt['field'])) {
/*            $options = array();
            echo $item->getValueToSelect($searchopt, $inputname, $_REQUEST['value'], $options);
            $display = true;
            break;*/
            // Specific cases
            switch ($searchopt['table'].".".$searchopt['field']) {
               case "glpi_reminders.state" :
                  Planning::dropdownState($inputname, $_REQUEST['value']);
                  $display = true;
                  break;


               case "glpi_changes.status" :
                  Change::dropdownStatus(array('name'     => $inputname, 
                                               'value'    => $_REQUEST['value'], 
                                               'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_changes.priority" :
                  Change::dropdownPriority(array('name'    => $inputname,
                                               'value'     => $_REQUEST['value'],
                                               'showtype'  => 'search',
                                               'withmajor' => true));
                  $display = true;
                  break;

               case "glpi_changes.impact" :
                  Change::dropdownImpact(array('name'     => $inputname,
                                               'value'    => $_REQUEST['value'],
                                               'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_changes.urgency" :
                  Change::dropdownUrgency(array('name'     => $inputname,
                                                'value'    => $_REQUEST['value'],
                                                'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_problems.status" :
                  Problem::dropdownStatus(array('name'    => $inputname, 
                                               'value'    => $_REQUEST['value'], 
                                               'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_problems.priority" :
                  Problem::dropdownPriority(array('name'    => $inputname,
                                               'value'     => $_REQUEST['value'],
                                               'showtype'  => 'search',
                                               'withmajor' => true));
                  $display = true;
                  break;

               case "glpi_problems.impact" :
                  Problem::dropdownImpact(array('name'     => $inputname,
                                               'value'    => $_REQUEST['value'],
                                               'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_problems.urgency" :
                  Problem::dropdownUrgency(array('name'     => $inputname,
                                                 'value'    => $_REQUEST['value'],
                                                 'showtype' => 'search'));                  $display = true;
                  break;

               case "glpi_tickets.status" :
                  Ticket::dropdownStatus(array('name'     => $inputname, 
                                               'value'    => $_REQUEST['value'], 
                                               'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_tickets.type" :
                  Ticket::dropdownType($inputname, array('value' => $_REQUEST['value']));
                  $display = true;
                  break;

               case "glpi_tickets.priority" :
                  Ticket::dropdownPriority(array('name'    => $inputname,
                                               'value'     => $_REQUEST['value'],
                                               'showtype'  => 'search',
                                               'withmajor' => true));
                  $display = true;
                  break;

               case "glpi_tickets.impact" :
                  Ticket::dropdownImpact(array('name'     => $inputname,
                                               'value'    => $_REQUEST['value'],
                                               'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_tickets.urgency" :
                  Ticket::dropdownUrgency(array('name'     => $inputname,
                                                'value'    => $_REQUEST['value'],
                                                'showtype' => 'search'));
                  $display = true;
                  break;

               case "glpi_tickets.global_validation" :
                  TicketValidation::dropdownStatus($inputname,
                                                   array('value'  => $_REQUEST['value'],
                                                         'global' => true,
                                                         'all'    => 1));
                  $display =true;
                  break;

               case "glpi_users.name" :
                  User::dropdown(array('name'     => $inputname,
                                       'value'    => $_REQUEST['value'],
                                       'comments' => false,
                                       'right'    => isset($searchopt['right'])
                                                      ?$searchopt['right'] :'all'));
                  $display = true;
                  break;

               case "glpi_ticketvalidations.status" :
                  TicketValidation::dropdownStatus($inputname, array('value' => $_REQUEST['value'],
                                                                     'all'   => 1));
                  $display = true;
                  break;

               case "glpi_ticketsatisfactions.type" :
                  Dropdown::showFromArray($inputname,
                                          array(1 => __('Internal survey'),
                                                2 => __('External survey')),
                                          array('value' => $_REQUEST['value']));
                  $display = true;
                  break;

               case "glpi_crontasks.state" :
                  CronTask::dropdownState($inputname, $_REQUEST['value']);
                  $display = true;
                  break;

               case "glpi_blacklists.type" :
                  Blacklist::dropdownType($inputname, array('value' => $_REQUEST['value']));
                  $display = true;
                  break;
            }

            // Standard datatype usage
            if (!$display && isset($searchopt['datatype'])) {
               switch ($searchopt['datatype']) {
                  case "bool" :
                     Dropdown::showYesNo($inputname, $_REQUEST['value']);
                     $display = true;
                     break;

                  case "right" :
                     // No access not displayed because empty not take into account for search
                     Profile::dropdownNoneReadWrite($inputname, $_REQUEST['value'], 1, 1, 1);
                     $display = true;
                     break;

                  case "itemtypename" :
                     Dropdown::dropdownUsedItemTypes($inputname,
                                                     getItemTypeForTable($searchopt['table']),
                                                     array('value'    => $_REQUEST['value'],
                                                           'comments' => 0));
                     $display = true;
                     break;

                  case "date" :
                  case "date_delay" :
                     Html::showGenericDateTimeSearch($inputname, $_REQUEST['value'],
                                                     array('with_time'   => false,
                                                           'with_future'
                                                               => (isset($searchopt['maybefuture'])
                                                                   && $searchopt['maybefuture'])));
                     $display = true;
                     break;

                  case "datetime" :
                     Html::showGenericDateTimeSearch($inputname, $_REQUEST['value'],
                                                     array('with_time'   => true,
                                                           'with_future'
                                                               => (isset($searchopt['maybefuture'])
                                                                   && $searchopt['maybefuture'])));
                     $display = true;
                     break;
               }
            }

            //Could display be handled by a plugin ?
            if (!$display
                && $plug = isPluginItemType(getItemTypeForTable($searchopt['table']))) {
               $function = 'plugin_'.$plug['plugin'].'_searchOptionsValues';
               if (function_exists($function)) {
                  $params = array('name'           => $inputname,
                                  'searchtype'     => $_REQUEST['searchtype'],
                                  'searchoption'   => $searchopt,
                                  'value'          => $_REQUEST['value']);
                  $display = $function($params);
               }
            }

            // Standard field usage
            if (!$display) {
               switch ($searchopt['field']) {
                  case "name" :
                  case "completename" :
                     $cond = (isset($searchopt['condition']) ? $searchopt['condition'] : '');
                     Dropdown::show(getItemTypeForTable($searchopt['table']),
                                    array('value'     => $_REQUEST['value'],
                                          'name'      => $inputname,
                                          'comments'  => 0,
                                          'condition' => $cond));
                     $display = true;
                     break;
               }
            }
        }
        break; //case "lessthan" :
   }

   // Default case : text field
   if (!$display) {
        echo "<input type='text' size='13' name='$inputname' value=\"".
               Html::cleanInputText($_REQUEST['value'])."\">";
   }
}
?>
