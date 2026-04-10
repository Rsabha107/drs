@extends('main.event.layout.event-add-layout')
@section('main')


<div class="content">

    <div id="chartPie" style="height: 350px;"></div>

</div>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

@endsection

@push('script')

<script>
    $(function() {

        'use strict'

        /**************** PIE CHART ************/
        var pieData = [{
            name: '',
            type: 'pie',
            radius: '80%',
            center: ['50%', '57.5%'],
            data: <?php echo json_encode($Data); ?>,
            label: {
                normal: {
                    fontFamily: 'Roboto, sans-serif',
                    fontSize: 11
                }
            },
            labelLine: {
                normal: {
                    show: false
                }
            },
            markLine: {
                lineStyle: {
                    normal: {
                        width: 1
                    }
                }
            }
        }];

        var pieOption = {
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: {c} ({d}%)',
                textStyle: {
                    fontSize: 11,
                    fontFamily: 'Roboto, sans-serif'
                }
            },
            legend: {},
            series: pieData
        };

        var pie = document.getElementById('chartPie');
        var pieChart = echarts.init(pie);
        pieChart.setOption(pieOption);
        /** making all charts responsive when resize **/
    });
</script>
@endpush
