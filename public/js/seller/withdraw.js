$(document).ready(function() {
    var TableAdvanced = function () {

        var initTable6 = function () {

            var table = $('#payment_history_table');

            /* Fixed header extension: http://datatables.net/extensions/keytable/ */

            var oTable = table.dataTable({
                // Internationalisation. For more info refer to http://datatables.net/manual/i18n
                "language": {
                    "aria": {
                        "sortAscending": ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "No data available in table",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries found",
                    "infoFiltered": "(filtered1 from _MAX_ total entries)",
                    "lengthMenu": "Show _MENU_ entries",
                    "search": "Search:",
                    "zeroRecords": "No matching records found"
                },
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                "pageLength": -1, // set the initial value,
                "columnDefs": [{  // set default column settings
                    'orderable': false,
                    'targets': [0]
                }, {
                    "searchable": false,
                    "targets": [0]
                }],
                "order": [
                    [1, "desc"]
                ]
            });

            var oTableColReorder = new $.fn.dataTable.ColReorder( oTable );

            var tableWrapper = $('#payment_history_table_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
            tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown


            $('#withdraw_label').pulsate({
                color: "#399bc3",
            });

            function check(address) {
                var decoded = base58_decode(address);
                if (decoded.length != 25) return false;

                var cksum = decoded.substr(decoded.length - 4);
                var rest = decoded.substr(0, decoded.length - 4);

                var good_cksum = hex2a(sha256_digest(hex2a(sha256_digest(rest)))).substr(0, 4);

                if (cksum != good_cksum) return false;
                return true;
            }

            function base58_decode(string) {
            var table = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
            var table_rev = new Array();

            var i;
            for (i = 0; i < 58; i++) {
                table_rev[table[i]] = int2bigInt(i, 8, 0);
            }

            var l = string.length;
            var long_value = int2bigInt(0, 1, 0);

            var num_58 = int2bigInt(58, 8, 0);

            var c;
            for(i = 0; i < l; i++) {
                c = string[l - i - 1];
                long_value = add(long_value, mult(table_rev[c], pow(num_58, i)));
            }

            var hex = bigInt2str(long_value, 16);

            var str = hex2a(hex);

            var nPad;
            for (nPad = 0; string[nPad] == table[0]; nPad++);

            var output = str;
            if (nPad > 0) output = repeat("\0", nPad) + str;

            return output;
            }

            function hex2a(hex) {
                var str = '';
                for (var i = 0; i < hex.length; i += 2)
                    str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
                return str;
            }

            function a2hex(str) {
                var aHex = "0123456789abcdef";
                var l = str.length;
                var nBuf;
                var strBuf;
                var strOut = "";
                for (var i = 0; i < l; i++) {
                nBuf = str.charCodeAt(i);
                strBuf = aHex[Math.floor(nBuf/16)];
                strBuf += aHex[nBuf % 16];
                strOut += strBuf;
                }
                return strOut;
            }

              function pow(big, exp) {
                  if (exp == 0) return int2bigInt(1, 1, 0);
                  var i;
                  var newbig = big;
                  for (i = 1; i < exp; i++) {
                      newbig = mult(newbig, big);
                  }

                  return newbig;
              }

              function repeat(s, n){
                  var a = [];
                  while(a.length < n){
                      a.push(s);
                  }
                  return a.join('');
              }

            $('#btn_save_btc_address').click(function() {
                var btc_address = $('#btc_address').val();
                if(btc_address == '') {
                    toastr['error']('Please enter BTC Address.');
                } else {
                    $.ajax({
                        url : '/seller/main/withdraw/chage_btc_address',
                        method : 'post',
                        data : {
                            btc_address : $('#btc_address').val()
                        },
                        success : function(data) {
                            toastr['success']('Changed successfully!');
                        },
                        error : function() {
                            toastr['error']('Happening any errors on changing BTC Address.');
                        }
                    });
                }

            });

            $('#btn_withdraw').click(function() {
                Metronic.blockUI({
                    target: '.page-content',
                    animate: true
                });
                $.ajax({
                    url : '/seller/main/withdraw/request',
                    method : 'post',
                    success : function(data) {
                        toastr['success']('Withdraw is requested successfully !');
                        var state_html = `<div class="alert alert-success text-left">
                        <h4 class="bold">Request submitted!</h4>
                        Your <b>Withdraw</b> request will be sent by admin soon!
                    </div>`;
                        $('#withdraw_state').html(state_html);
                        Metronic.unblockUI('.page-content');
                    },
                    error : function() {
                        toastr['error']('Happening any errors on request withdraw !');
                        Metronic.unblockUI('.page-content');
                    }
                })
            });
        }

        return {

            //main function to initiate the module
            init: function () {

                if (!jQuery().dataTable) {
                    return;
                }

                initTable6();
            }

        };

    }();

    TableAdvanced.init();
})
