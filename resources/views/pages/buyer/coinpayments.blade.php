@extends('layout.main')

@section('page_js')
    <script src="{{ asset('js/buyer/balance.js') }}"></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script>
        $(document).ready(function() {
        	@if($remaining_seconds>0)
            getTimer('#payment_timeout', {{$remaining_seconds}});
        	getStatus('{{$order->txn_id}}');
            @endif
        });
        
        var clipboard = new ClipboardJS('.btn-copy');

        clipboard.on('success', function(e) 
        {
            $(e.trigger).attr('data-bs-original-title', "Copied!");         
            e.clearSelection();
        });

        function getStatus(txn_id)
        {
        	$('#payment_status').html('Checking...');
        	
        	$.ajax({
        		url:'{{url("coinpayments/status")}}',
        		data:'txn_id='+txn_id,
        		type:'get',
        		dataType:'json',
        		success:function(json){				
        			$('#payment_status').html(json.status_text);					
        			
        			if(json.status!='100') {
        				setTimeout('getStatus("'+txn_id+'")', (10*1000)); 				
        			}else{
        				// Transaction Completed	
        				alert('Congrats! Fund Received!');			
        			}
        		}
        	});	
        }
        function getTimer(div, timer) {
            //alert(seconds);
        	setInterval(function () {
        		
        		hours = parseInt(timer / 3600, 10);
        		rem_mins = timer - (hours*3600);
        		mins = parseInt(rem_mins / 60, 10);
        		seconds = parseInt(timer % 60, 10);
        			
        		if (timer == 0) {
        			$(div).html('Expired!');
        			return false;
        		} else {
        			hours = hours < 10 ? "0" + hours : hours;
        			mins = mins < 10 ? "0" + mins : mins;
        			seconds = seconds < 10 ? "0" + seconds : seconds;
        			
        			$(div).html(hours+'h : '+mins+'m : '+seconds+'s');
        			timer--;
        		}
        	}, 1000);			
        }
    </script>
@endsection

@section('page-container')
<!-- BEGIN PAGE HEAD -->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>My Balance <small>Complete your payment</small></h1>
        </div>
        <!-- END PAGE TITLE -->
    </div>
</div>
<!-- END PAGE HEAD -->
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-body" style="padding-top:20px;">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <p><img src="{{$order->qrcode_url}}" class="img-fluid img-thumbnail" /></p>
                                <p><small class="form-text">Scan this image to get the deposit address</small></p>
                            </div>
                            <div class="col-md-4">
                                <p>
                                    <label>Status: </label> <label class="bold"><span id="payment_status">{{$order->status_text}}</span></label>
                                </p>
                                <p>
                                    <label>Txn ID: </label> <label class="bold label label-primary">{{$order->txn_id}}</label>
                                </p>
                                <p>
                                    <label>Deposit Amount: </label> <label class="bold"><font class="text-primary">{{$order->amount}}</font> USD</label>
                                </p>
                                <p>
                                    <label>Total Amount to Send: </label> 
                                    <label class="bold"><font class="text-primary">{{$order->coin_amount}}</font> {{$order->coin_currency}}</label>
                                    <button title="Copy" data-bs-toggle="tooltip" type="button" class="btn btn-sm btn-danger active btn-copy" data-clipboard-text="{{$order->coin_amount}}">Copy <i class="fa fa-clipboard"></i></button>
                                </p>
                                <p>
                                    <label>Send To Address: </label> 
                                    <label class="bold label label-primary">{{$order->coin_address}}</label> 
                                    <button title="Copy" data-bs-toggle="tooltip" type="button" class="btn btn-sm btn-danger active btn-copy" data-clipboard-text="{{$order->coin_address}}">Copy <i class="fa fa-clipboard"></i></button>
                                </p>
                                <p>
                                    @if($remaining_seconds>0)
                                        <label>Time Left: </label>
                                        <label class="bold"><span id="payment_timeout">{{$remaining_seconds}}</span> seconds</label> <small> (expire on {{$order->expire_at}})</small>
                                    @else
                                        <label>Time Left: </label>
                                        <p class="text-danger"><label class="bold">Expired at {{$order->expire_at}}</label></p>
                                        <p class="mb-0">Don't send Bitcoin after transaction expired. Create new deposit transaction.</p>
                                    @endif
                                </p>
                                <p>
                                    <label>Created On: </label> 
                                    <label class="bold">{{$order->created_at}}</label>
                                </p>
                            </div>
                            <div class="col-md-5">
                                 <div class="well">
                                     <ul>
                                         <li>
                                             <label class="bold">DO NOT CLOSE THIS PAGE</label>
                                         </li>
                                         <li>
                                             <label>Please wait for at least 1 confirmation</label>
                                         </li>
                                         <li>
                                             <label>For high amounts please include high fees</label>
                                         </li>
                                         <li>
                                             <label>Bitcoin to USD rate is <label class="text-primary bold">{{ $btc_rate }}</label><b class="text-danger">$</b> (according to Blockchain)</label>
                                         </li>
                                         <li>
                                             <label>Our bitcoin addresses are SegWit-enabled</label>
                                         </li>
                                         <li>
                                             <label>This page will be only valid for <b>2 hours</b></label>
                                         </li>
                                         <li>
                                             <label>Make sure that you send exactly <b class="text-primary">{{$order->coin_amount}}</b><b class="text-danger">{{$order->coin_currency}}</b></label>
                                         </li>
                                         <li>
                                             <label><b>Do Not try</b> to Send Less than <b class="text-primary">{{$order->coin_amount}}</b><b class="text-danger">{{$order->coin_currency}}</b>. It will not work and may transaction fail</label>
                                         </li>
                                         <li>
                                             <label>After payment an amount of <b class="text-primary">{{$order->amount}}</b><b class="text-danger">$</b> will be added to your account</label>
                                         </li>
                                         <li>
                                             <label>If any error happened or money didn't show please  <a href="{{ url('/ticket') }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Open a Ticket</a> Fast</label>
                                         </li>
                                     </ul>
                                 </div>
                            </div>
                        </div>
                    </div><hr>
                    <div class="portlet-footer text-center">
                        <!--<a class="btn btn-success" target="_blank" href="{{ $order->status_url}}">View Details</a>-->
                        <a class="btn btn-light" href="{{url('balance')}}">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
