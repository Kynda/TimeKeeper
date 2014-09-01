var TimeKeeper = ( function( $ ) {
    
    var my = {
        'dom': {
            'forms': {
                'edit': null
            },
            'input': {
                'datepicker': null,
                'date': null,
                'account': null,
                'task': null,
                'tasks': null,
                'billable': null,
                'return_uri': null,
                'end': null,
                'start': null,
                'account': null,
                'accounts': null,
                'hours': null,
                'alltime': null
            },
            'containers': {
                'times': null,
                'totals': null,
                'add': null,
                'filter': null
            },
            'links': {
                'add': null,
                'filter': null,
                'delete': null,
                'cancel_delete': null,
                'confirm_delete': null,
                'thead': null
            },
            'alerts': {
                'save': null,
                'delete': null
            }
        }
    };
    
    _datePicker = function() {
        $( my.dom.input.datepicker ).datepicker({ 
            'dateFormat': 'yy-mm-dd',
            'numberOfMonths': 3,
            'showButtonPanel': true
        } );
    };  
    
    _hourCalculator = function() {
        
        var $start = $(my.dom.forms.edit + ' ' + my.dom.input.start);
        var $end = $(my.dom.forms.edit + ' ' + my.dom.input.end);                
        var change = function() {
                       
            start  = Date.parse( new Date().toDateString() + ' ' + $start.val() );
            end    = Date.parse( new Date().toDateString() + ' ' + $end.val() );
            
            var hours = ( end - start ) / 1000 / 60 / 60;
            
            $(my.dom.input.hours).val( hours );
            
        };                                                    
            
        $start.change( change );
        $end.change( change );
        $start.addClass('bound');        
    };
    
    _setDefaults = function() {
        
        paths = window.location.pathname.split('/');
        
        $(my.dom.input.date).val( ( paths[2] != '' && paths[2] !=  undefined && paths[2] != 'any' ) ? paths[2] : $(my.dom.input.date).val() );
        $(my.dom.input.account).val( ( paths[4] != '' && paths[4] != 'any' && paths[4] != undefined ) ? paths[4].split(',')[0].replace(/\%20/g, ' ') : null );
        $(my.dom.input.task).val( ( paths[5] != '' && paths[5] != 'any' && paths[5] != undefined ) ? paths[5].split(',')[0].replace(/\%20/g, ' ') : null );
        $(my.dom.input.billable)[0].checked = ( paths[6] != 'nonbillable' ) ? true : false;  
        
        $(my.dom.input.return_uri).val( window.location.pathname );        
        
    };
    
    _updateOrderLinks = function() {
        
        paths = window.location.pathname.split('/');
        
        if( paths[7] )
        {
            var order = paths[7].search('asc') === -1 ? 'asc' : 'desc';
            
            $.each( $(my.dom.links.thead), function( index, value ) {
               
               this.href = this.href.replace('asc', order);
                
            });
        }
        
    };    
    
    _insertEditForm = function( data ) {
        
            $add = $(my.dom.containers.add );
            $add.empty();
            $add.append( data );
            $add.addClass('active');

            _datePicker();
            _hourCalculator();
            my.onEditSubmit();
            
            $( my.dom.input.return_uri ).val( window.location.pathname );
            $( my.dom.input.account ).autocomplete({ source: accounts });
            $( my.dom.input.task ).autocomplete({ source: tasks });
            $( my.dom.links.add ).tab( 'show' );                
    };

    _insertFilterForm = function( data ) {
    
            $filter = $(my.dom.containers.filter );
            $filter.empty();
            $filter.append( data );
            $filter.addClass('active');

            _datePicker();
            
            $( my.dom.input.return_uri ).val( window.location.pathname );
            $( my.dom.input.account ).autocomplete({ source: accounts });
            $( my.dom.input.task ).autocomplete({ source: tasks });
            $( $filter ).tab( 'show' );                
    };
    
    _refreshTimesTable = function( data ) {
        
        $times = $(my.dom.containers.times);
        $times.empty();
        $times.append( $( data ).find( my.dom.containers.times ).children() );

        $totals = $(my.dom.containers.totals);
        $totals.empty();
        $totals.append( $( data ).find( my.dom.containers.totals ).children() );
        
    };
    
    my.onEditSubmit = function() {
        
        $editForm = $(my.dom.forms.edit );
        
        $editForm.submit( function( e ) {
            
            e.preventDefault();
            
            $hours = $(my.dom.input.hours);
            $hours.removeAttr('disabled');
            
            $.post('/save',  $editForm.serialize(), function( data ) {
                
                $hours.attr('disabled', 'disabled');
                my.oldEnd = $(my.dom.forms.edit + ' ' + my.dom.input.end).val();
                
                _refreshTimesTable( data );
                
                $alert = $( my.dom.alerts.save );
                $alert.show();
                setTimeout( function() {
                    $alert.hide();
                    $( my.dom.links.add ).click();
                }, 500 );
                
            });
            
        });
        
    };
    
    my.onEditEvent = function() {
        
        $edit = $(my.dom.links.edit);
        
        if( $edit.data('bound') === true )
        {
                return;
        }
        
        $edit.click( function( e ) {
           
            e.preventDefault();
            
            $(this).data('bound', true);
            
            $.get( this.href, function( data ) {
                
                my.oldEnd = null;
                
                _insertEditForm( data );                                
                
                window.scrollTo(0,0);
                
            });
            
        });
        
    };
    
    my.onAddEvent = function() {
        
        $add = $(my.dom.links.add);
        
        if( $add.data('bound') === true )
        {        
            return;            
        }
        
        $add.click( function( e ) {
            
            e.preventDefault();
            
            $(this).data('bound', true);
            
            $.get('/edit', function( data ) {                                      
                
                _insertEditForm( data );
                _setDefaults();
                
                if( !my.oldEnd ) {
                    my.oldEnd = '08:30:00';
                }
                
            } );
            
        });
        
    };

    my.onFilterEvent = function() {
    
        $filter = $(my.dom.links.filter);

        if( $filter.data('bound') === true )
        {
            return;
        }

        $filter.click( function( e ) {


            $url = window.location.pathname.replace('/list', '/filter');
            if( $url === '' || $url === '/' )
            {
                $url = '/filter';
            }

            $.get( $url, function( data ) {
            
                _insertFilterForm( data );
                _setDefaults();
                my.onAccountChange();
                $(my.dom.input.accounts).change();
                
                my.onAllTimeChange();
                $( my.dom.input.alltime );
        
                if( !my.oldEnd ) {
                    my.oldEnd = '08:30:00';
                }

            } );

        })
    }
    
    my.onDeleteEvent = function() {
        
        $delete = $( my.dom.links.delete );
        
        if( $delete.data('bind') === 'bound' )
        {
            return;
        }
        
        $delete.click( function( e ) {
            
            e.preventDefault();
            
            $(this).addClass('bound');
            
            var that = this;
            
            $(my.dom.links.cancel_delete).click( function() {
                $( my.dom.links.confirm_delete ).unbind('click');
                $( my.dom.alerts.delete ).hide();
            })
            
            $(my.dom.links.confirm_delete).click( function() {
                
                var posted = {
                    'id': that.href.split('/').splice(-1)[0],
                    'return_uri': window.location.pathname
                };
                
                $.post( '/delete', posted, function( data ) {
                    
                    _refreshTimesTable( data );
                    
                    $add = $(my.dom.links.add);
                    if( $add.parents().hasClass('active') )
                    {
                        $add.click();
                    }
                    
                    $( my.dom.alerts.delete ).hide();
                    
                })
                
            })  
            
            $(my.dom.alerts.delete).show();
            
        });                
        
    };    
    
    my.onAccountChange = function() {
        
        $accounts = $(my.dom.input.accounts);
        
        $accounts.change( function() {
            
            $.post('/json/tasks', { 'accounts': $accounts.val() }, function( data ) {
                
                $tasks = $(my.dom.input.tasks);
                
                $tasks.empty();
                $.each( data, function( index, value ) {
                    $tasks.append( '<option value="'+value.task+'">'+value.task+'</option>');
                } );                 
            }, 'json' );
            
        });                        
    };
    
    my.onAllTimeChange = function() {
        
        paths = window.location.pathname.split('/');
        
        var $start = $( my.dom.input.start );
        var $end = $( my.dom.input.end );
        var $alltime = $( my.dom.input.alltime );
        
        var disableTime = function() {
            $start.attr('disabled', 'disabled').val('any');
            $end.attr('disabled', 'disabled').val('any');
        }
        
        var enableTime = function() {
            
            if( ( !paths[2] && !paths[3] ) || (paths[2] === 'any' && paths[3] === 'any') )
            {               
                paths[2] = paths[3] = new Date().format('yyyy-mm-dd');
            }   
            
            $start.removeAttr('disabled').val( paths[2] );
            $end.removeAttr('disabled').val( paths[3] );              
            
        }
        
        if( paths[2] === 'any' && paths[3] === 'any' )
        {
            $alltime[0].checked = true;
            disableTime();
        }    
        
        $alltime.change( function() {

            if( this.checked )
            {
                disableTime();
            }
            else
            {
                enableTime();
            }
        });        
        
    };
    
    my.start = function( dom ) {
        
        if( typeof dom === 'object' )
        {
            my.dom = dom;
        }
        
        $(document).ready( function() {
            _datePicker();     
            _updateOrderLinks();            
            my.onEditEvent();
            my.onFilterEvent();
            my.onAddEvent();
            my.onDeleteEvent();            

            $(dom.links.filter).click();
        });
        
        $(document).ajaxComplete( function() {
           _datePicker();
           _updateOrderLinks();
           my.onEditEvent();
           my.onDeleteEvent();

            if( my.oldEnd )
            {
                $start = $(my.dom.forms.edit + ' ' + my.dom.input.start);
                $end = $(my.dom.forms.edit + ' ' + my.dom.input.end);
                $start.val( my.oldEnd );
                $end.val( my.oldEnd );                  
            }           
        });
        
    };
    
    return my;
    
}( jQuery ) );
