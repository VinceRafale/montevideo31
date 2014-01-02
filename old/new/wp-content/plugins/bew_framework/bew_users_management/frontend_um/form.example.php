<?php

  $config = Array(

    'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => 'inputText example',
      'submit' => 1,  // include submit button in this fieldset
    ),

    'name' => Array(
      'type'           => 'inputText',
      'displayname'    => 'Enter your name',
      'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),

    'acceptrules' => Array(
      'type'           => 'inputCheckbox',
      'displayname'    => 'Do you accept<br>site rules?',
      'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'help'           => 'You have to accept the site rules to continue',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),

    'drink[]' => Array(
      'type'           => 'select',
      'displayname'    => 'Drink <br>(columns swapped for this row using <code>rowlayout</code>)',
      'help'           => 'Select some drink!',
      'rowlayout'      => 
        '<tr %errorstyle%>'.
          '<td width="120" align="right">%prefix%%element%%postfix%%errordiv%</td>'.
          '<td width="15">%erroricon%</td>'.
          '<td align="left"><label for="%id%">%displayname%</label></td></tr>' .
          "\n",
      'values'         => Array( 
        0 => 'cafe', 
        1 => 'hot chocolate', 
        2 => 'milk',
        3 => 'yoghurt' ),
      'html'           => 'multiple="multiple" size="5"',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),

    'think' => Array(
      'type'           => 'inputRadio',
      'displayname'    => 'What do you think?<br />(the tabular layout for the radio buttons is done using <code>layout</code> and <code>itemlayout</code>)',
      'help'           => 'Please tell us what you think!',
      'values'         => Array( 
        0 => 'no',
        1 => 'yes',
        2 => 'maybe',
      ),
      'layout'     => '<table>%s</table>',
      'itemlayout' => '<tr><td>%radio%</td><td>%label%</td></tr>',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),

  );
