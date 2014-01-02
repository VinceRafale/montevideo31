<?php

class clonefish {

  var $name;                    // <form name="...">
  var $id;                      // <form id="...">
  var $action;	                // <form action="...">
  var $method;	                // <form method="...">

  var $codepage;
  var $multibytesupport = 'multibyteutf8';
  var $multibytesetup   = Array(
    'none' => Array( // Single byte charsets only! Affected by setlocale.
      'strlen'   => 'strlen( "%s" )',
      'regexp'   => 'preg_match( "%s", "%s" )', 
    ),
    'multibyteutf8' => Array( // Best bet for utf-8, uses preg!
      'strlen'   => 'mb_strlen( \'%s\' )',
      'regexp'   => 'preg_match( \'%su\', \'%s\' )',     // utf-8 only
      'encoding' => 'mb_internal_encoding( \'%1$s\' ) && mb_regex_encoding( \'%1$s\' )'
    ),
    'multibyte' => Array( // Even for multibyte charsets, uses ereg
      'strlen'   => 'mb_strlen( \'%s\' )',
      'regexp'   => 'mb_ereg( \'%s\', \'%s\', $reg )',          // ereg itself is deprecated
      'encoding' => 'mb_internal_encoding( \'%1$s\' ) && mb_regex_encoding( \'%1$s\' )'
    ),
    'multibyteci' => Array( // Even for multibyte charsets, uses case insensitive ereg
      'strlen'   => 'mb_strlen( \'%s\' )',
      'regexp'   => 'mb_eregi( \'%s\', \'%s\', $reg )',        // ereg itself is deprecated
      'encoding' => 'mb_internal_encoding( \'%1$s\' ) && mb_regex_encoding( \'%1$s\' )'
    ),
    'iconv' => Array( // Single byte charsets only, if mb_* support is missing
      'strlen'   => 'iconv_strlen( \'%s\' )',
      'regexp'   => 'preg_match( \'%s\', \'%s\' )',
      'encoding' => 'iconv_set_encoding( \'internal_encoding\', \'%s\' )'
    ),
    'iconvutf8' => Array( // UTF-8 only, if mb_* support is missing
      'strlen'   => 'iconv_strlen( \'%s\' )',
      'regexp'   => 'preg_match( \'%su\', \'%s\' )',
      'encoding' => 'iconv_set_encoding( \'internal_encoding\', \'%s\' )'
    )
  );

  var $elements = Array();      // array holding elements

  // path to clonefish.js file
  var $jspath  = 'clonefish/clonefish.js';
  var $js      = 1;             // to disable javascript validation

  // ERROR MESSAGE SETTINGS FOR CLIENT SIDE ERRORS
  var $jsalert = 1;
  var $jsalertmessagecontainerlayout = '%s';
  var $jsalertmessagelayout          = '- %s\n';

  var $jshtml  = 1;
  var $jshtmlmessagecontainerlayout = '%s';
  var $jshtmlmessagelayout          = '%s<br />';

  // ERROR MESSAGE SETTINGS FOR SERVER SIDE ERRORS
  var $messages        = Array();
  var $messageoutput   = Array();
  var $outputmessages  = 1;  // to disable message output

  var $messageprefix   = ''; // displayed above error messages.
                             // set to CF_STR_FORM_ERRORS (defined 
                             // in messages_XX.php) 
                             // by the constructor

  var $messagecontainerlayout = "<div class=\"errors\">%s</div>\n";
  var $messagelayout          = "<div class=\"error\">%s</div>\n";
  var $messagepostfix  = ''; // displayed below the error messages

  var $showerroricon   = 1;
  var $errorstyle      = ' class="error" ';
  //var $erroricon       = '<img src="images/error.gif" />';

  // FORM LAYOUT SETTINGS
  var $prefix          = ''; // displayed above the form
  var $postfix         = ''; // displayed below the form

  var $formopenlayout  = "<form enctype=\"multipart/form-data\" target=\"%target%\" id=\"%id%\" name=\"%name%\" action=\"%action%\" %onsubmit% method=\"%method%\">\n";
  var $formcloselayout = "</form>\n";
  var $target = "_self";
  var $onbeforesubmit  = '';

  var $submit = 'OK';
  var $nosubmit = false; // don't print submit button automatically

  // ELEMENT LAYOUT SETTINGS

  var $layouts = Array(

  'tabular' => Array(
    'container'  => "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\">\n%s\n</table>\n",
    'element'    => "<tr %errorstyle%><td width=\"120\" align=\"right\"><label for=\"%id%\">%displayname%</label></td><td width=\"7\" class=\"center\">%erroricon%</td><td>%prefix%%element%%postfix%%errordiv%</td></tr>\n",
    'errordiv'   => '<div id="%divid%" style="display: none; visibility: hidden; padding: 2px 5px 2px 5px; background-color: #d03030; color: white;"></div>',
    'buttonrow'  => '<tr><td colspan="2"></td><td>%s</td></tr>',
    'button'     => '<input type="submit" value="%s" />',
  ),

  'rowbyrow' => Array(
    'container'  => '%s',
    'element'    => "<label for=\"%id%\">%displayname%</label> %erroricon%<br />\n%prefix%%element%%postfix%%errordiv%\n<br /><br />\n",
    'errordiv'   => '<div id="%divid%" style="display: none; visibility: hidden; padding: 2px 5px 2px 5px; background-color: #d03030; color: white;"></div>',
    'buttonrow'  => '%s',
    'button'     => '<input type="submit" value="%s" />',
  ),

  );

  // MISCELLANEOUS
  var $layout         = 'tabular';
  var $layoutcleanup  = true; // clean up unnecessary label tags (when displayname is empty, do not show <label for="xxx"></label> )

  var $db;               // reference to the database wrapper
  var $dbtype;           // type of database: determines 
                         // filename (dbwrapper_DBTYPE.php) and
                         // classname (DBWrapperDBTYPE)

  var $configfilter;     // a function name to call when
                         // a config file has been loaded

  var $invalidated = 0;  // programmatically invalidate the form

  // -------------------------------------------------------------------------
  function clonefish( $name, $action, $method, $db = null, $dbtype = false, $refid = null ) {

    $this->name   = $name;
    $this->id     = $name;
    $this->action = $action;
    $this->method = $method;
    $this->refid  = $refid;
    $this->dbtype = $dbtype;

    $this->messageprefix = CF_STR_FORM_ERRORS;

    if ( !defined( 'CLONEFISH_DIR' ) )  
      define('CLONEFISH_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

    include_once( CLONEFISH_DIR . 'constants.php');
    include_once( CLONEFISH_DIR . 'element.php');
    include_once( CLONEFISH_DIR . 'validation.php');

    if ( $this->dbtype ) {

      // we should have a wrapper object available
      if ( file_exists( CLONEFISH_DIR . 'dbwrapper_' . strtolower( $this->dbtype ) . '.php' ) ) {
        include_once( CLONEFISH_DIR . 'dbwrapper_' . strtolower( $this->dbtype ) . '.php');
        $classname    = 'DBWrapper' . $this->dbtype;
        $this->db     = new $classname( $db );
      }
      else
        die( sprintf( CF_ERR_DBWRAPPER_DBTYPE_UNKNOWN, $this->dbtype ) );

    }

  }

  // -------------------------------------------------------------------------
  function addElements( $elements, $values = false, $slashes_added = null ) {

    if ( ( $values !== false ) && ( $slashes_added === null ) )
      die( sprintf( CF_ERR_MISSING_SLASHES_ADDED_PARAMETER, 'addElements()' ) );

    foreach ( $elements as $key => $value ) {

      if ( !isset( $value['type'] ) ) 
        die( sprintf( CF_ERR_CONFIG_TYPE_MISSING, $key ) );

      if ( 
           isset( $value['validation'] ) &&
           !empty( $values['validation'] ) &&
           !is_array( current( $value['validation'] ) )
         )
        die( 
          sprintf( CF_ERR_CONFIG_VALIDATION_NOT_AN_ARRAY_OF_ARRAYS, $key ) 
        );

      $elementtype = strtolower( $value['type'] );

      $classfile = 
        CLONEFISH_DIR . 'element.' . $elementtype . '.php';

      if ( file_exists( $classfile ) ) {
        include_once( $classfile );
        $element = new $value['type']( $key, $value, $this->db, $this->refid );
        $element->form = &$this;
      }
      else
        die( sprintf( CF_ERR_CONFIG_TYPE_UNSUPPORTED, $key, $value['type'], $classfile ) );

      if (
           is_array( $values ) &&
           count( $values ) &&
           !$element->readonly
         ) {

        // array hack: associative arrays

        if (
             preg_match( '/^([^\[\]]+)((\[([^\[\]]*)\])+)$/', $element->name, $parts ) &&
             preg_match_all( '/\[([^\[\]]+)\]/', $element->name, $indexes ) 
           ) {

          if ( isset( $values[ $parts[1] ] ) ) {

            $arrayvalue = $values[ $parts[1] ];
            // remove empty [] from the end of the name
            $parts[2] = preg_replace('/(\[\])+$/', '', $parts[2] );
            // add quotes: [name] => ['name']
            $parts[2] = preg_replace('/\[(.*\D.*)\]/U', '[\'\1\']', $parts[2] );

            // avoid indexes being not numeric, but string in fact,
            // for example: 00000002
            if (
                 (
                   strlen( trim( $parts[2], '[]' ) ) !=
                   strlen( (int) trim( $parts[2], '[]' ) )
                 ) &&
                 !preg_match( '/^\'.+\'$/', trim( $parts[2], '[]' ) )
               ) {
              // add quotes: [name] => ['name']
              $parts[2] = preg_replace('/\[(.*)\]/U', '[\'\1\']', $parts[2] );
            }

            $code = '$found = @$arrayvalue' . $parts[2] . ';';
            eval( $code );

            if ( $found !== false )
              $element->setValue( $found, $slashes_added );

          }

        }

        // array hack: [] arrays
        elseif ( preg_match( '/^(.+)\[\]$/', $element->name, $parts ) ) {
          if ( is_array( @$values[ $parts[1] ] ) )
            $element->setValue( $values[ $parts[1] ], $slashes_added );
        }
        elseif ( isset( $values[ $element->getName() ] ) ) {

          // normal case: we've received a value

          switch ( $elementtype ) {

            case 'mapmarker':

              if (
                   !isset( $values[ $element->getName() . 'lat' ] ) &&
                   !isset( $values[ $element->getName() . 'lng' ] )
                 )
                // only the value itself was given: it must be
                // a date in compiled format, like when 
                // a form is created from a database record
                $element->setValue(
                  $values[ $element->getName() ], $slashes_added 
                );
              else {
                $element->setValue(
                  @$values[ $element->getName() . 'lat' ] .
                  $element->glue .
                  @$values[ $element->getName() . 'lng' ],
                  $slashes_added
                );
              }
              break;

            case 'selectdate':

            // selectdate parts needs to be 'compiled' into a date.
            // we cannot trust the attached hidden field, as it's
            // created by JS, which can be disabled

            if ( 
                 !isset( $values[ $element->getName() . 'year' ] ) &&
                 !isset( $values[ $element->getName() . 'month' ] ) &&
                 !isset( $values[ $element->getName() . 'day' ] ) &&
                 !isset( $values[ $element->getName() . 'timeshort' ] )
               ) {
              // only the value itself was given: it must be
              // a date in compiled format, like when 
              // a form is created from a database record
              $element->setValue(
                $values[ $element->getName() ], $slashes_added 
              );
            }
            else
              // also received at least one helper part:
              // then we have a form submitted at hand, so
              // we need to compile the date (if there's
              // no JS support, we will receive only an empty hidden
              // field, that's why we cannot trust that)

              $element->createStoredFormat( 
                @$values[ $element->getName() . 'year'      ],
                @$values[ $element->getName() . 'month'     ],
                @$values[ $element->getName() . 'day'       ],
                @$values[ $element->getName() . 'timeshort' ],
                $slashes_added 
              );

              break;

            default:
              // all the other inputs
              $element->setValue( $values[ $element->getName() ], $slashes_added );
              break;

          }
        }
        else {
          // checkboxes are missing from $_POST/$_GET if 
          // they're unchecked
          if ( $elementtype == 'inputcheckbox' )
            $element->setValue( $element->offvalue, 0 );
          if ( $elementtype == 'inputcheckboxdynamic' )
            $element->setValue( Array(), 0 );
        }
      }
      else
        if ( isset( $value['value'] ) )
          $element->setValue( $value['value'], 0 );

      $this->addelement( $element );
      unset( $element );

    }

  }

  // -------------------------------------------------------------------------
  function addElement( $object ) {

    $this->elements[] = &$object;

  }

  // -------------------------------------------------------------------------
  function getValidationJSCode() {
    
    $code    = '';
    $message = '';

    foreach ( $this->elements as $key => $object ) {

      $validationsettings = $this->elements[ $key ]->getValidationSettings();
      foreach ( $validationsettings as $vskey => $validationparameters ) {

        if ( ( $vskey === 'anddepend' ) || ( $vskey === 'ordepend' ) )
          continue;

        if ( !is_array( $validationparameters ) ) 
          die( sprintf( CF_ERR_CONFIG_VALIDATION_NOT_AN_ARRAY_OF_ARRAYS, $object->getName() ) );

        $validationparameters['form'] = &$this;
        if ( !isset( $validationparameters['type'] ) ) 
          die( sprintf( CF_ERR_CONFIG_VALIDATION_TYPE_MISSING, $object->getName() ) );

        // inherit dependancy parameters to each validation
        if ( isset( $validationsettings['anddepend'] ) )
          $validationparameters['anddepend'] = $validationsettings['anddepend'];

        if ( isset( $validationsettings['ordepend'] ) )
          $validationparameters['ordepend'] = $validationsettings['ordepend'];
        
        switch ( $validationparameters['type'] ) {
        
          case 'required':

            $validator = &$this->getRequiredClassFor( 
              $this->elements[ $key ], $validationparameters
            );
            
            $code     .= $validator->getJSCode();
            break;

          default:

           // die( sprintf( STR_CLONEFISH_FREE_VERSION, $object->getname(), $validationparameters['type'] ) ); 
            break;

        }

      }

    }

    $code             = preg_replace('/^(.*)$/Umsi', '    \\1', $code );
    $javascript_trans = Array(
      "\n" => '\n',
      "'"  => "\\'",
      "\t" => '\t',
    );

    return
      "<!--\n" .
      "\n" .
      "function check_" . $this->name . "() {\n" .
      "\n" .
      "  errors = new clonefishErrors();\n" .
      "  errors.useAlert = " . ( $this->jsalert ? 'true' : 'false' ) . ";\n" .
      "  errors.useHTML  = " . ( $this->jshtml  ? 'true' : 'false' ) . ";\n" .
      ( $this->jshtml ?
        "  errors.messageContainerLayout      = '" . strtr( $this->jshtmlmessagecontainerlayout, $javascript_trans ) . "';\n" .
        "  errors.messageLayout               = '" . strtr( $this->jshtmlmessagelayout, $javascript_trans ) . "';\n"
      : "" ) .
      ( $this->jsalert ?
        "  errors.alertMessageContainerLayout = '" . strtr( $this->jsalertmessagecontainerlayout, $javascript_trans ) . "';\n" .
        "  errors.alertMessageLayout          = '" . strtr( $this->jsalertmessagelayout, $javascript_trans ) . "';\n" 
      : "" ) .
      "\n" .
      "  // validation code\n" .
      $code .
      "\n" .
      "  // show messages if needed\n" . 
      "  errors.go();\n" .
      "\n" .
      "  if ( !errors.empty )\n" .
      "    return false;\n" .
      "  else {\n" .
      "    // onbeforesubmit code\n" .
      (
        strlen( $this->onbeforesubmit ) ? $this->onbeforesubmit : "" 
      ) .
      "    return true;\n" .
      "  }\n" .
      "\n" .
      "  }\n" .
      "// -->\n"
    ;

  }

  // -------------------------------------------------------------------------
  function getVars() {

   // die( STR_CLONEFISH_FREE_VERSION );

  }

  // -------------------------------------------------------------------------
  function getHTML() {

    // hidden fields are collected first
    // and are put before the form table
    // so it won't break the html code of the table

    $fields       = '';
    $hiddenfields = '';
    $messages = $this->messages;

    // the following foreach will
    //   1) collect field html data
    //   2) if a fieldset element is 
    //        found, closes previous fieldset (if there's any)
    //        and starts collecting fields into the current
    //        fieldset's content
    //   3) place the submit button inside the fieldset if
    //        the $fieldset_element->submit property is set to 1
    //   4) will only display the submit button below the form if
    //        it was not previously printed with a fieldset element

    $currentfieldset = false;
    $collect         = '';
    $postfix         = '';
    $submitbutton    =
      sprintf(
        $this->layouts[ $this->layout ]['buttonrow'], 
        sprintf( $this->layouts[ $this->layout ]['button'],
          $this->submit 
        )
      )
    ;
    
    $submitprinted = 0;
    $fieldsetcount = null;
    $javascripts   = Array();

    foreach ( $this->elements as $key => $object ) {

      if ( $this->elements[ $key ]->type != 'inputHidden' ) {

        if ( ( $fieldsetcount !== null ) && ( $fieldsetcount >= 0 ) )
           $fieldsetcount--;

        if ( is_object( $currentfieldset ) && ( $fieldsetcount === -1 ) ) {

          // the field counter for this fieldset is now empty,
          // let's flush the current fieldset

          $postfix = $currentfieldset->submit ? $submitbutton : '';

          $fields .=
            sprintf(
              $currentfieldset->getHTMLRow( $this->layout, $this->errorstyle, $this->showerroricon, $this->erroricon ),
              sprintf(
                $this->layouts[ $this->layout ]['container'] . "\n",
                $collect . $postfix
              )
            );
          $currentfieldset = false;
          $fieldsetcount   = false;
          $collect         = '';
          $postfix         = '';

        }

        if ( $this->elements[ $key ]->type == 'fieldset' ) {

          $fieldsetcount =
            $this->elements[ $key ]->value > 0 ?
              $this->elements[ $key ]->value : false
          ;

          if ( is_object( $currentfieldset ) ) {

            // we've found a fieldset that's running

            if ( $currentfieldset->submit ) {
              $postfix = $submitbutton;
              $submitprinted = 1;
            }
            else
              $postfix = '';

            // let's flush the running fieldset 

            $fields .=
              sprintf(
                $currentfieldset->getHTMLRow( 
                  $this->layout, $this->errorstyle, $this->showerroricon, $this->erroricon 
                ),
                sprintf(
                  $this->layouts[ $this->layout ]['container'] . "\n",
                  $collect . $postfix 
                )
              );
          }
          else
            // we've found a fieldset after some fields: flush
            // current fields 
            if ( strlen( $collect ) )
            $fields .= 
              sprintf(
                $this->layouts[ $this->layout ]['container'] . "\n",
                $collect . $postfix 
              )
            ;
          
          // we're starting the current (or new) fieldset
          $currentfieldset = $this->elements[ $key ];
          $collect = '';

        }
        else
          // not a fieldset: store
          $collect .= $this->elements[ $key ]->getHTMLRow( $this->layout, $this->errorstyle, $this->showerroricon, $this->erroricon );
      }
      else
        $hiddenfields .= $this->elements[ $key ]->getHTMLRow( $this->layout, $this->errorstyle, $this->showerroricon, $this->erroricon );

      $javascripts[ $this->elements[ $key ]->type ] = 
        $this->elements[ $key ]->getScripts();

      $messages =
        array_merge( $messages, $this->elements[ $key ]->getMessages() );
    }

    // we've finished: flush fieldset

    if ( is_object( $currentfieldset ) ) {

      $postfix = '';
      if ( $currentfieldset->submit ) {
        $postfix = $submitbutton;
        $submitprinted = 1;
      }

      $fields .= 
        sprintf( 
          $currentfieldset->getHTMLRow( $this->layout, $this->errorstyle, $this->showerroricon, $this->erroricon ),
          sprintf(
            $this->layouts[ $this->layout ]['container'],
            $collect . $postfix
          ) 
        );

      $collect = '';

    }
      
    if ( strlen( $collect ) || !$submitprinted ) 
      $fields .= 
        sprintf(
          $this->layouts[ $this->layout ]['container'] . "\n",
          $collect .
            ( $submitprinted || $this->nosubmit ? '' : $submitbutton )
        )
      ;

    $messages = array_unique( $messages );
    $validationcode = $this->getValidationJSCode();

    $replace = Array(
       '%id%'       => $this->id,
       '%name%'     => $this->name,
       '%action%'   => $this->action,
       '%target%'   => $this->target,
       '%method%'   => $this->method,
       '%onsubmit%' => $this->js ? " onsubmit=\"return check_" . $this->name . "();\" " : "" 
    );

    // form open tag
    $out = $this->prefix . strtr( $this->formopenlayout, $replace );

    // messages
    $this->messageoutput = array_merge( $this->messageoutput, $messages );
    if ( $this->outputmessages && count( $this->messageoutput ) ) {
      $allmessages = '';
      foreach ( $this->messageoutput as $onemessage )
        $allmessages .= sprintf( $this->messagelayout, $onemessage );

      $out .= 
        $this->messageprefix . 
        sprintf( $this->messagecontainerlayout, $allmessages ) .
        $this->messagepostfix;
        
    }

    // fields and closing tag
    $out .= 
      implode( '', $javascripts ) .
      $hiddenfields . 
      $fields .
      $this->formcloselayout . 
      $this->postfix .
      "\n"
    ;

    return
      ( $this->js ?
      "<script src=\"" . $this->jspath . "\" type=\"text/javascript\"></script>\n" .
      "<script type=\"text/javascript\">" . $validationcode . "</script>\n" 
      : ""
      ) .
      $out;

  }

  // -------------------------------------------------------------------------
  function getJSName() {
    return 'document.forms["'. $this->name .'"]';
  }
  
  // -------------------------------------------------------------------------
  function getName() {
    return $this->name;
  }

  // -------------------------------------------------------------------------
  function removeElement( $field ) {
  
    foreach ( $this->elements as $key => $object ) {
      if ( $this->elements[ $key ]->getRealName() === $field ) {
        unset( $this->elements[ $key ] );
        return true;
      }
    }

    return false;
    
  }  

  // -------------------------------------------------------------------------
  function &getElementByName( $field ) {
  
    foreach ( $this->elements as $key => $object ) {
      if ( $this->elements[ $key ]->getRealName() === $field ) {
        $object = &$this->elements[ $key ];
        return $object;
      }
    }

    $notfound = false;
    return $notfound;

  }  

  // -------------------------------------------------------------------------
  function addMessage( $message ) {

    if ( !in_array( $message, $this->messages ) )
      $this->messages[] = $message;

  }  

  // -------------------------------------------------------------------------
  function getMessages() {

    $messages = $this->messages;

    foreach ( $this->elements as $key => $object )

      $messages =
        array_merge( $messages, $this->elements[ $key ]->getMessages() );

    return $messages;

  }

  // -------------------------------------------------------------------------
  function getElementValues( $magic_quotes_gpc, $asArray = false ) {

    $values = Array();
    $arrays = Array();

    foreach ( $this->elements as $key => $object ) {
      if ( 
           !in_array( $this->elements[ $key ]->type, 
              Array( 'fieldset', 'text', 'template' ) 
           )
         )
        if ( 
             $asArray &&
             preg_match_all( '/^([^\[]+)(\[.*\])+$/', $this->elements[ $key ]->getName(), $results )
           ) {

          $results[2][0] = preg_replace( '/\[([^\[\]]*)\]/', '[\'\\1\']', $results[2][0] );
          $cmd = '
             $arrays[\'' . $results[1][0] . '\']' . $results[2][0] . ' = 
              $this->elements[ $key ]->getValue( $magic_quotes_gpc );
            ';

          eval( $cmd );

        }
        else
        $values[ $this->elements[ $key ]->getName() ] =
          $this->elements[ $key ]->getValue( $magic_quotes_gpc );
    }

    if ( $asArray )
      foreach ( $arrays as $key => $array ) 
        $values[ $key ] = $array;

    return $values;

  }

  // -------------------------------------------------------------------------
  function invalidate() {
    $this->invalidated = 1;
  }

  // -------------------------------------------------------------------------
  function validate() {

    $valid   = Array();

    foreach ( $this->elements as $key => $object ) {

      // never use $object here because as that's only a 
      // _copy_ of the object
      foreach ( $this->elements[ $key ]->getvalidationsettings() as $validationparameters ) {

        $validationparameters['form'] = &$this;

        switch ( $validationparameters['type'] ) {

          case 'required':
            $validator = $this->getRequiredClassFor( 
              $this->elements[ $key ], $validationparameters
            );
            
            break;

          default:

           // die( sprintf( STR_CLONEFISH_FREE_VERSION, $validationparameters['type'] ) ); 
            break;

        }

      }

      $valid = array_merge(
        $valid,
        $this->validateElement( null, $this->elements[ $key ] )
      );

    }

    return $this->invalidated ? false : count( $valid ) == 0;

  }

  // -------------------------------------------------------------------------
  function validateElement( $name = null, &$element = null ) {

    if ( !is_object( $element ) )
      $element =& $this->getElementByName( $name );

    if ( $element->validated )
      return $element->validationarray;

    $element->validating = true;

    $valid = Array();
    $validationsettings = $element->getValidationSettings();

    foreach ( $validationsettings as $vskey => $validationparameters ) {

      if ( 
           ( $vskey === 'anddepend' ) || 
           ( $vskey === 'ordepend' )
         )
        continue;

      $validationparameters['form'] = &$this;

      if ( isset( $validationsettings['anddepend'] ) )
        $validationparameters['anddepend'] = $validationsettings['anddepend'];

      if ( isset( $validationsettings['ordepend'] ) )
        $validationparameters['ordepend'] = $validationsettings['ordepend'];

        switch ( $validationparameters['type'] ) {

          case 'required':
            $validator = $this->getRequiredClassFor( 
              $element, $validationparameters
            );
            
            break;

          default:

            $classfile = 
              CLONEFISH_DIR . 'validation.' . strtolower( $validationparameters['type'] ) . '.php';

            if ( file_exists( $classfile ) ) {
              include_once( $classfile );
              $class = $validationparameters['type'] . 'Validation';
            $validator = new $class( $validationparameters, $element );
            }
            else
              die( sprintf( CF_ERR_PHPVALIDATOR_UNSUPPORTED, $this->elements[ $key ]->name, $validationparameters['type'] ) ); 

            break;

        }

        $valid = array_merge( $valid, $validator->isValid() );

      }

    $element->validating      = false;
    $element->validationarray = $valid;
    $element->valid           = count( $valid ) == 0;
    $element->validated       = true;

    return $valid;

  }

  // -------------------------------------------------------------------------
  function &getRequiredClassFor( &$element, $validationparameters ) {

    $fallbacks = 
      Array(
        'inputradiodynamic' => 'inputradio',
        'selectdynamic'     => 'select',
        'selectfile'        => 'select',
        'inputhidden'       => 'inputtext',
        'fckeditorarea2'        => 'fckeditorarea2',
        'fckeditorarea2_bbcode' => 'fckeditorarea2',
        'inputpassword'     => 'inputtext', 
        'textarea'          => 'inputtext',
      );

    $elementtype = strtolower( $element->getType() );
    if ( isset( $fallbacks[ $elementtype ] ) ) 
      $elementtype = $fallbacks[ $elementtype ];

    $classfile   = 'validation.required.' . $elementtype . '.php';

    if ( file_exists( CLONEFISH_DIR . $classfile ) ) {
      include_once( $classfile );
      $class     = $elementtype . 'Required';
      $validator = new $class( $validationparameters, $element, $this );
      return $validator;

    }
    else 
      die( sprintf( CF_ERR_REQUIRE_UNSUPPORTED, $element->getName(), $element->getType() ) );

  }

  // -------------------------------------------------------------------------
  function getValue( $elementname, $magic_quotes_gpc ) {
    
    $element = $this->getElementByName( $elementname );

    if ( is_object( $element ) )
      return $element->getValue( $magic_quotes_gpc );
    else
      return false;
 
  }

  // -------------------------------------------------------------------------
  function setValue( $elementname, $value, $magic_quotes_gpc ) {
    
    $element = $this->getElementByName( $elementname );

    if ( is_object( $element ) )
      return $element->setValue( $value, $magic_quotes_gpc );
    else
      return false;
 
  }

  // ----------------------------------------------------------------------------
  function loadConfig( $filename ) {

    $f        = fopen( $filename, 'r' );

    $settings = Array();
    $row      = fgets( $f, 4096 );
    $continue = $row !== false;

    while ( $continue ) {

      $thisrow = fgets( $f, 4096 );

      if (
           ( ( 
               strlen( trim( $thisrow ) ) &&
               !in_array(
                 substr( $thisrow, 0, 1 ),
                 Array( ' ', "\t" ) 
               )
             ) ) ||
           $thisrow === false
         ) {

        $row = rtrim( $row, "\n\r" );

        if ( substr( $row, 0, 1 ) != ';' ) {

          $parts = explode( 
            '=', 
            $this->_configfilter( $this->configfilter, $row ), 
            2 
          );
          
          $parts[ 0 ] = trim( $parts[ 0 ] );
          
          if ( strlen( $parts[ 0 ] ) ) {
  
            // instead of trim(), we use preg_match + substr
            // to ensure symmetrical trimming
            while ( preg_match('/^\"(.+)\"$/', $parts[1] ) )
              $parts[1] = substr( $parts[1], 1, -1 );

            $parts[ 1 ] = 
              preg_replace(
                '/\\\([nrt"])/e', "\"\\\\$1\"", $parts[ 1 ]
              );

            if ( strpos( $parts[ 0 ], '.' ) !== false ) {

              // array setting
              $nameparts = explode('.', $parts[ 0 ] );
              $settings  = $this->_create_array( $nameparts, $settings, 0, $parts[ 1 ] );

            }
            else {
              $settings[ $parts[ 0 ] ] = $parts[ 1 ];
            }

          }

        }

        $continue = false;
        $row = '';

      }

      if ( $thisrow !== false )
        $continue = true;

      if ( strlen( trim( $thisrow, "\n" ) ) ) 
        $row .= $thisrow;

    }

    foreach ( $settings as $key => $value ) 
      if ( is_array( $value ) ) {
        $this->$key =  
          $this->better_array_merge(
            $this->$key, 
            $settings[ $key ] 
          );
      }
      else
        $this->$key = $value;

  }

  // ----------------------------------------------------------------------------
  function better_array_merge( $array1, $array2 ) {

    foreach ( $array1 as $key => $value ) 
      if ( isset( $array2[ $key ] ) ) {
        if ( is_array( $array2[ $key ] ) )
          $array1[ $key ] = $this->better_array_merge( $array1[ $key ], $array2[ $key ] );
        else
          if ( !is_array( $array1[ $key ] ) )
            $array1[ $key ] = $array2[ $key ];
      }

    return $array1;

  }

  // ----------------------------------------------------------------------------
  function _create_array( $nameparts, $settings, $counter, $leafvalue ) {

    // used by loadconfig

    if ( !isset( $settings[ $nameparts[ $counter ] ] ) ) {
      if ( $counter == ( count( $nameparts ) - 1 ) )
        $settings[ $nameparts[ $counter ] ]  = $leafvalue;
      else
        $settings[ $nameparts[ $counter ] ]  = 
          $this->_create_array( 
            $nameparts, 
            Array(), 
            $counter + 1, 
            $leafvalue );
    } 
    else
      if ( $counter == ( count( $nameparts ) - 1 ) )
        $settings[ $nameparts[ $counter ] ]  = $leafvalue;
      else
        $settings[ $nameparts[ $counter ] ]  = 
          $this->_create_array( 
            $nameparts, 
            $settings[ $nameparts[ $counter ] ], 
            $counter + 1, 
            $leafvalue );
    
    return $settings;
    
  }

  // ----------------------------------------------------------------------------
  function _configFilter( $configfilter, $value ) {

    if ( $configfilter )
      return $configfilter( $value );
    else
      return $value;
   
  }

  // -------------------------------------------------------------------------
  function _normalizePath( $path ) {

    if ( substr( $path, strlen( $path ) - 1, 1 ) != '/' )
      return $path . '/';
    else
      return $path;

  }

  // -------------------------------------------------------------------------
  function insertIntoArrayBefore( $array, $index, $newItem, $newKey = null ) {

    $result = Array();

    foreach ( $array as $key => $item ) {
      if ( $key == $index ) {
        if ( $newKey !== null )
          $result[ $newKey ] = $newItem;
        else
          $result[] = $newItem;
      }
      $result[ $key ] = $item;
    }

    return $result;

  }

  // -------------------------------------------------------------------------
  function _functionSupported( $function ) {

    if ( !strlen( $this->codepage ) )
      die( CF_ERR_MBSUPPORT_CODEPAGE_MISSING );

    elseif ( !array_key_exists( $this->multibytesupport, $this->multibytesetup ) )
      die(
        sprintf(
          CF_ERR_MBSUPPORT_INVALID_PARAMETER,
          $this->multibytesupport,
          "'" . implode("', '", array_keys( $this->multibytesetup ) ) . "'"
        )
      );

    elseif ( !in_array( $function, Array( 'encoding', 'strlen', 'regexp' ) ) )
      die(
        sprintf(
          CF_ERR_MBSUPPORT_FUNCTION_UNIMPLEMENTED, 
          $function
        )
      );

    else
      
      return true;

  }

  // -------------------------------------------------------------------------
  function _handleString( $function, $value, $parameter = null ) {

    if ( $this->_functionSupported( $function ) ) {

      $setup = $this->multibytesetup[ $this->multibytesupport ];

      if ( isset( $setup['encoding'] ) ) {
        $cmd = sprintf( $setup[ 'encoding' ], $this->codepage );
        eval( '$encodingvalue = ' . $cmd . ';' );

        if ( !$encodingvalue )
          die(
            sprintf( CF_ERR_MBSUPPORT_CODEPAGE_UNSUPPORTED,
              $this->codepage,
              $this->multibytesupport,
              $cmd
            )
          );

      }

      switch ( $function ) {

        case 'regexp':
          $cmd = sprintf( $setup['regexp'], $parameter, $value );
          break;
        case 'strlen':
          $cmd = sprintf( $setup['strlen'], $value );
          break;
      
      }

      eval( '$value = ' . $cmd . ';' );
      return $value;

    }

  }

}

?>