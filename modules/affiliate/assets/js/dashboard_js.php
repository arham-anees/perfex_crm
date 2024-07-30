<script>
var fnServerParams = {
    "status": '[name="order_status"]',
  };
(function($) {
  "use strict";
  var data = {};
  requestGet('affiliate/dashboard_transaction_chart').done(function(response) {
    response = JSON.parse(response);
    Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('transaction_chart', {
         chart: {
             type: 'column'
         },
         title: {
             text: 'Transactions chart'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: response.month,
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: response.unit,
            data: response.data,
            showInLegend: false
         }]
     });
        
  })

  requestGet('affiliate/dashboard_commission_chart').done(function(response) {
    response = JSON.parse(response);
    Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('commission_chart', {
         chart: {
             type: 'column'
         },
         title: {
             text: 'Commissions chart'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: response.month,
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: response.unit,
            data: response.data,
            showInLegend: false
         }]
     });
        
  })

  requestGet('affiliate/dashboard_discount_chart').done(function(response) {
    response = JSON.parse(response);
    Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('discount_chart', {
         chart: {
             type: 'column'
         },
         title: {
             text: 'Discounts chart'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: response.month,
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: response.unit,
            data: response.data,
            showInLegend: false
         }]
     });
        
  })

  requestGet('affiliate/dashboard_registration_chart').done(function(response) {
    response = JSON.parse(response);
    Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'inherit !important',
              fill: 'black'
          }
      },
      colors: [ '#119EFA','#ef370dc7','#15f34f','#791db2d1', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4','#50B432','#0d91efc7','#ED561B']
     });
        Highcharts.chart('registration_chart', {
         chart: {
             type: 'column'
         },
         title: {
             text: 'Registrations chart'
         },
         subtitle: {
             text: ''
         },
         credits: {
            enabled: false
          },
         xAxis: {
             categories: response.month,
             crosshair: true,
         },
         yAxis: {
             min: 0,
             title: {
              text: response.name
             }
         },
         tooltip: {
             headerFormat: '<span>{point.key}</span><table>',
             pointFormat: '<tr>' +
                 '<td><b>{point.y:.0f} {series.name}</b></td></tr>',
             footerFormat: '</table>',
             shared: true,
             useHTML: true
         },
         plotOptions: {
             column: {
                 pointPadding: 0.2,
                 borderWidth: 0
             }
         },

         series: [{
            type: 'column',
            colorByPoint: true,
            name: '',
            data: response.data,
            showInLegend: false
         }]
     });
        
  })
  
    initDataTable('.table-affiliate-orders', admin_url + 'affiliate/affiliate_order_table', false, false, fnServerParams);
})(jQuery);
</script>