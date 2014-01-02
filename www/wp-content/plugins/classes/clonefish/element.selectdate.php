<?php

/**
 * Clonefish form generator class 
 * (c) phpformclass.com, Dots Amazing
 * All rights reserved.
 * 
 * @copyright  2010 Dots Amazing
 * @link       http://phpformclass.com
 * @package    clonefish
 * @subpackage elements
 */

/* 
 * Element
 * @package clonefish
 * @subpackage elements
 */
class selectDate extends element {

  var $value;

  var $layout = '%Y %M %D';
    // we use % here to avoid problems with Y, M and D letters
    // in the layout string.
    // eg. if you'd like to use a select like
    //   Year: [    ] Month: [    ] Day: [    ]
    // you should specify:
    //   $layout = 'Year: %Y Month: %M Day: %D'

  var $format = '%Y-%M-%D';
    // format is used to specify the 'compiled' result of
    // the selects returned by getValue

  var $padding      = true; 
    // store month and day with two digits 
    // (01..12, 01..31 )

  var $null         = Array( '' => '' );
    // if $null is not false, but an array, it is used
    // for enabling empty dates. You may set it like:
    // Array( '' => '-- choose --' ) and combine
    // the element with a date validation.

  var $yearfrom  = false; // if ===false, will be set to current year in constructor
  var $yearuntil = 1900;  // if ===false, will be set to current year in constructor

  // if $yearfrom is larger than $yearuntil, you'll get a 
  // decrementing list of years, which is more natural to the users
  
  var $months    = Array( 
    1 => CF_STR_MONTH_01,  2 => CF_STR_MONTH_02,  3 => CF_STR_MONTH_03,  4 => CF_STR_MONTH_04, 
    5 => CF_STR_MONTH_05,  6 => CF_STR_MONTH_06,  7 => CF_STR_MONTH_07,  8 => CF_STR_MONTH_08, 
    9 => CF_STR_MONTH_09, 10 => CF_STR_MONTH_10, 11 => CF_STR_MONTH_11, 12 => CF_STR_MONTH_12
  );
  // you'll find the months defined in messages_XX.php 
  // You can still override the month array by
  // the 'months' setting of your element.

  var $onbeforechange; 
  var $onafterchange; 
  // should you need to update anything when the dropdowns change,
  // just include your JS code here (trailing ";" required)

  // private variables
  var $year      = null;
  var $month     = null;
  var $day       = null;
  var $timeshort = null; // hh:mm

  // --------------------------------------------------------------------------
  function selectDate( $name, $configvalues ) {

    $this->name           = $name;

    foreach ( $configvalues as $key => $value )
      if ( $key != 'value' )
        $this->$key = $value;

    if ( $this->yearfrom === false )
      $this->yearfrom = date("Y");

    if ( $this->yearuntil === false )
      $this->yearuntil = date("Y");

    if ( isset( $configvalues['value'] ) ) 
      $this->setValue( $configvalues['value'], 0 );

  }

  // --------------------------------------------------------------------------
  function setValue( $value, $magic_quotes_gpc ) {

    // besides the date value, we also maintain
    // private variables - $year, $month, $day -
    // which are needed to reload the selects
    // with selected values

    $value = $this->_prepareInput( $value, $magic_quotes_gpc );

    // $this->value = $value;
    //
    // we don't set value unless it's a date than can 
    // be processed by strtotime. setting value is done
    // by $this->createStoredFormat()

    if ( class_exists( 'DateTime' ) ) {

      // above PHP 5.2, we have the DateTime object
      // also supporting historical dates, and better
      // date support
       
      if ( preg_match( '/^([^\d]|0)+$/', $value ) )
        // 0000-00-00 type dates are convenient 
        // database values instead of NULLs, which are
        // converted to Nov 30, -0001 by PHP, so we
        // need to have a workaround here instead.
        // It will never break date compatibility, as it's
        // a wrong date format (zero month and day).
        $time = false;
      else {
        $time    = new DateTime( $value );
        if ( is_object( $time ) ) {
          $year    = $time->format("Y");
          $month   = $time->format("m");
          $day     = $time->format("d"); 
          $hourmin = $time->format("H:i");
        }
      }
    
    }
    else {
      $time      = strtotime( $value );
      if ( $time ) {  
        $year      = date("Y", $time );
        $month     = date("m", $time );
        $day       = date("d", $time );
        $hourmin   = date("H:i", $time );
      }
    }

    if ( $time ) {

      // we now have valid time parts, but
      // are the values between yearfrom/yearuntil?
      return $this->createStoredFormat(
        $year, $month, $day, $hourmin, 0
      );
  
    }
    else
      return false;

  }

  // --------------------------------------------------------------------------
  function getHTML() {

    $selects = Array();

    $years     = $this->_createrange( $this->yearfrom, $this->yearuntil );
    $months    = $this->months;
    $days      = $this->_createrange( 1, 31 );
    $timeshort = $this->timeshort;

    if ( is_array( $this->null ) && count( $this->null ) ) {
      $years     = $this->null + $years;
      $months    = $this->null + $months;
      $days      = $this->null + $days;
    }

    $parts['%Y']  = $this->makeSelect( $this->_getHTMLId() . 'year',  $years,  $this->year );
    $parts['%M']  = $this->makeSelect( $this->_getHTMLId() . 'month', $months, $this->month );
    $parts['%D']  = $this->makeSelect( $this->_getHTMLId() . 'day',   $days,   $this->day );
    $parts['%T']  = $this->makeInput( $this->_getHTMLId() . 'timeshort', '00:00', $this->timeshort );

    $out = strtr( $this->layout, $parts );

    // the hidden input is a helper field containing
    // the current date in a 'compiled' form (according to
    // 'format' setting)

    return
      '<input type="hidden" ' . $this->html .
        ' id="' . $this->_getHTMLId() . '"' .
        ' value="' . $this->value . '"' .
        ' name="' . $this->name . '" />' . "\n" .
      $out . "\n"
    ;

  }

  // -------------------------------------------------------------------------
  function makeInput( $name, $options, $value ) {

    return
      '<input name="' . $name . '" id="' . $name . '" ' .
        'maxlength="5" size="5" type="text" ' .
        'value="' . htmlspecialchars( $value ) . '" ' .
        'onchange="' . $this->onbeforechange . 'clonefishSelectDateStoredFormat( ' .
          'document.forms[\'' . $this->form->name . '\'], ' .
          '\'' . $this->_getHTMLId() . '\', ' .
          '\'' . $this->format . '\', ' .
          '\'' . $this->padding . '\' ' .
          ');' . $this->onafterchange .
        "\" />\n";

  }

  // -------------------------------------------------------------------------
  function makeSelect( $name, $options, $value, $from = 1 ) {

    $out = '';
    foreach ( $options as $key => $avalue ) {

      if ( $key == $value )
        $out .= '<option selected="selected" value="' . 
          htmlspecialchars( $key ) . 
        '">';
      else
        $out .= '<option value="' . htmlspecialchars( $key ) . '">';

      $out .= htmlspecialchars( $avalue ) . "</option>\n";

    }
 
    return
      "<select " .
        "onchange=\"" . $this->onbeforechange . "clonefishSelectDateStoredFormat( " .
          "document.forms['" . $this->form->name . "'], " .
          "'" . $this->_getHTMLId() . "', ".
          "'" . $this->format . "', ".
          "'" . $this->padding . "' ".
          ");" . $this->onafterchange .
        "\" name=\"" . $name . "\">\n" . $out . "</select>\n";

  }

  // -------------------------------------------------------------------------
  function createStoredFormat( $year, $month, $day, $timeshort, $magic_quotes_gpc ) {

    // used by $clonefish->addElements(): the values of the received
    // date part selects are compiled also on the server side
    // to support server-side validation (we cannot rely purely on JS)
    //
    // also used by $this->setValue(): when a formatted date is
    // received, it's being split using strtotime(), and 
    // the element value is set to the stored format, this
    // way we can always match with the format settings, even
    // when unnecessary date elements (eg. seconds) are
    // passed from a database query as a value.

    $year      = $this->_prepareInput( $year,      $magic_quotes_gpc );
    $month     = $this->_prepareInput( $month,     $magic_quotes_gpc );
    $day       = $this->_prepareInput( $day,       $magic_quotes_gpc );
    $timeshort = $this->_prepareInput( $timeshort, $magic_quotes_gpc );

    $out   = '';

    for ( $i = 0; $i < strlen( $this->format ); $i++ ) {

      switch ( substr( $this->format, $i, 2 ) ) {
        case '%Y':
          while ( strlen( $year ) < 4 )
            $year = '0' . $year;
          $out .= $year;
          $i++;
          break;                                      
        case '%M':
          while ( $this->padding && ( strlen( $month ) < 2 ) )
            $month = '0' . $month;
          $out .= $month;
          $i++;
          break;                                      
        case '%D':
          while ( $this->padding && ( strlen( $day ) < 2 ) )
            $day = '0' . $day;
          $out .= $day;
          $i++;
          break;
        case '%T':
          while ( $this->padding && ( strlen( $timeshort ) < 2 ) )
            $timeshort = '0' . $timeshort;
          $out .= $timeshort;
          $i++;
          break;
        default:
          $out .= substr( $this->format, $i, 1 );
          break;
      }

    }

    $reverse = $this->yearfrom > $this->yearuntil;

    if (
         (
           ( $this->yearfrom !== false ) &&
           (
             ( !$reverse && ( $year < $this->yearfrom ) ) ||
             (  $reverse && ( $year > $this->yearfrom ) )
           )
         )
         ||
         (
           ( $this->yearuntil !== false ) &&
           (
             ( !$reverse && ( $year > $this->yearuntil ) ) ||
             (  $reverse && ( $year < $this->yearuntil ) )
           )
         )
       ) {
      // invalid date passed, out of year range
      return false;
    }
    else {

      $this->value     = $out;
      $this->year      = $year;
      $this->month     = $month;
      $this->day       = $day;
      $this->timeshort = $timeshort;
      return true;

    }

  }

  // -------------------------------------------------------------------------
  function _createRange( $from, $until ) {

    $range = Array();

    for (
          $i = $from;
          $from < $until ? $i <= $until : $i >= $until;
          $from < $until ? $i++ : $i--
        )
      $range[ $i ] = $i;

    return $range;

  }

}

?>