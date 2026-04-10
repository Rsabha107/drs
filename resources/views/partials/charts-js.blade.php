<!-- {{ $remaining_budget }} -->

<script>
    (function(factory) {
        typeof define === 'function' && define.amd ? define(factory) :
            factory();
    })((function() {
        'use strict';

        // import * as echarts from 'echarts';
        const {
            merge
        } = window._;

        // form config.js
        const echartSetOption = (
            chart,
            userOptions,
            getDefaultOptions,
            responsiveOptions
        ) => {
            const {
                breakpoints,
                resize
            } = window.phoenix.utils;
            const handleResize = options => {
                Object.keys(options).forEach(item => {
                    if (window.innerWidth > breakpoints[item]) {
                        chart.setOption(options[item]);
                    }
                });
            };

            const themeController = document.body;
            // Merge user options with lodash
            chart.setOption(merge(getDefaultOptions(), userOptions));

            const navbarVerticalToggle = document.querySelector(
                '.navbar-vertical-toggle'
            );
            if (navbarVerticalToggle) {
                navbarVerticalToggle.addEventListener('navbar.vertical.toggle', () => {
                    chart.resize();
                    if (responsiveOptions) {
                        handleResize(responsiveOptions);
                    }
                });
            }

            resize(() => {
                chart.resize();
                if (responsiveOptions) {
                    handleResize(responsiveOptions);
                }
            });
            if (responsiveOptions) {
                handleResize(responsiveOptions);
            }

            themeController.addEventListener(
                'clickControl',
                ({
                    detail: {
                        control
                    }
                }) => {
                    if (control === 'phoenixTheme') {
                        chart.setOption(window._.merge(getDefaultOptions(), userOptions));
                    }
                }
            );
        };
        // -------------------end config.js--------------------

        const echartTabs = document.querySelectorAll('[data-tab-has-echarts]');
        if (echartTabs) {
            echartTabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', e => {
                    const el = e.target;
                    const {
                        hash
                    } = el;
                    const id = hash || el.dataset.bsTarget;
                    const content = document.getElementById(id.substring(1));
                    const chart = content?.querySelector('[data-echart-tab]');
                    if (chart) {
                        window.echarts.init(chart).resize();
                    }
                });
            });
        }

        const tooltipFormatter = (params, dateFormatter = 'MMM DD') => {
            let tooltipItem = ``;
            params.forEach(el => {
                tooltipItem += `<div class='ms-1'>
        <h6 class="text-body-tertiary"><span class="fas fa-circle me-1 fs-10" style="color:${
          el.borderColor ? el.borderColor : el.color
        }"></span>
          ${el.seriesName} : ${
      typeof el.value === 'object' ? el.value[1] : el.value
    }
        </h6>
      </div>`;
            });
            return `<div>
            <p class='mb-2 text-body-tertiary'>
              ${
                window.dayjs(params[0].axisValue).isValid()
                  ? window.dayjs(params[0].axisValue).format(dateFormatter)
                  : params[0].axisValue
              }
            </p>
            ${tooltipItem}
          </div>`;
        };

        const handleTooltipPosition = ([pos, , dom, , size]) => {
            // only for mobile device
            if (window.innerWidth <= 540) {
                const tooltipHeight = dom.offsetHeight;
                const obj = {
                    top: pos[1] - tooltipHeight - 20
                };
                obj[pos[0] < size.viewSize[0] / 2 ? 'left' : 'right'] = 5;
                return obj;
            }
            return null; // else default behaviour
        };

        const {
            echarts: echarts$2
        } = window;
        /* -------------------------------------------------------------------------- */
        /*                        Echarts budget Utilizatization                      */
        /* -------------------------------------------------------------------------- */

        const budgetUtilizationtInit = () => {
            const {
                getData,
                getColor
            } = window.phoenix.utils;
            const $chartEl = document.querySelector('.echarts-budget-utilization');
            // var percent = $(this).data('percent');
            const percent = getData($chartEl, 'percent');
            console.log('inside echarts-budget-utilization');
            console.log('percent: ' + percent);

            if ($chartEl) {
                const userOptions = getData($chartEl, 'options');
                const chart = echarts$2.init($chartEl);
                console.log('inside chartEl: ' + $chartEl);

                const getDefaultOptions = () => ({
                    tooltip: {
                        trigger: 'item',
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        position: (...params) => handleTooltipPosition(params),
                        transitionDuration: 0,
                        formatter: params => {
                            return `<strong>${params.seriesName}:</strong> ${params.value}%`;
                        }
                    },
                    legend: {
                        show: false
                    },
                    series: [{
                        type: 'gauge',
                        center: ['50%', '60%'],
                        name: 'Utilized Budget',
                        startAngle: 180,
                        endAngle: 0,
                        min: 0,
                        max: 100,
                        splitNumber: 12,
                        itemStyle: {
                            color: getColor('primary')
                        },
                        progress: {
                            show: true,
                            roundCap: true,
                            width: 12,
                            itemStyle: {
                                shadowBlur: 0,
                                shadowColor: '#0000'
                            }
                        },
                        pointer: {
                            show: false
                        },
                        axisLine: {
                            roundCap: true,
                            lineStyle: {
                                width: 12,
                                color: [
                                    [1, getColor('primary-bg-subtle')]
                                ]
                            }
                        },
                        axisTick: {
                            show: false
                        },
                        splitLine: {
                            show: false
                        },
                        axisLabel: {
                            show: false
                        },
                        title: {
                            show: false
                        },
                        detail: {
                            show: true
                        },
                        data: [{
                            value: percent,
                            // name: 'raafat',
                        }, ]
                    }]
                });

                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        // const { echarts } = window;

        /* -------------------------------------------------------------------------- */
        /*                                Market Share                                */
        /* -------------------------------------------------------------------------- */

        const topCouponsChartInit = () => {
            const {
                getData,
                getColor
            } = window.phoenix.utils;
            const ECHART_TOP_COUPONS = '.echart-top-coupons-tracki';
            const $echartTopCoupons = document.querySelector(ECHART_TOP_COUPONS);

            if ($echartTopCoupons) {
                const userOptions = getData($echartTopCoupons, 'options');
                const chart = echarts.init($echartTopCoupons);

                const getDefaultOptions = () => ({
                    color: [
                        getColor('primary'),
                        getColor('primary-lighter'),
                        getColor('info-dark')
                    ],

                    tooltip: {
                        trigger: 'item',
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        position(pos, params, el, elRect, size) {
                            const obj = {
                                top: pos[1] - 35
                            }; // set tooltip position over 35px from pointer
                            if (window.innerWidth > 540) {
                                if (pos[0] <= size.viewSize[0] / 2) {
                                    obj.left = pos[0] + 20; // 'move in right';
                                } else {
                                    obj.left = pos[0] - size.contentSize[0] - 20;
                                }
                            } else {
                                obj[pos[0] < size.viewSize[0] / 2 ? 'left' : 'right'] = 0;
                            }
                            return obj;
                        },
                        formatter: params => {
                            return `<strong>${params.data.name}:</strong> ${params.percent}%`;
                        }
                    },
                    legend: {
                        show: false
                    },
                    series: [{
                        name: '72%',
                        type: 'pie',
                        radius: ['100%', '87%'],
                        avoidLabelOverlap: false,
                        emphasis: {
                            scale: false,
                            itemStyle: {
                                color: 'inherit'
                            }
                        },
                        itemStyle: {
                            borderWidth: 2,
                            borderColor: getColor('body-bg')
                        },
                        label: {
                            show: true,
                            position: 'center',
                            formatter: '{a}',
                            fontSize: 23,
                            color: getColor('light-text-emphasis')
                        },
                        data: [{
                                value: 7200000,
                                name: 'Percentage discount'
                            },
                            {
                                value: 1800000,
                                name: 'Fixed card discount'
                            },
                            {
                                value: 1000000,
                                name: 'Fixed product discount'
                            }
                        ]
                    }],
                    grid: {
                        containLabel: true
                    }
                });

                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        /* -------------------------------------------------------------------------- */
        /*                     Echart Bar Member info                                 */
        /* -------------------------------------------------------------------------- */

        const newCustomersChartsTrackiInit = () => {

            const {
                getColor,
                getData,
                getDates
            } = window.phoenix.utils;
            const $echartNewCustomersTrackiCharts = document.querySelector(
                '.echarts-new-customers-tracki'
            );
            const tooltipFormatter = params => {
                const currentDate = window.dayjs(params[0].axisValue);
                const prevDate = window.dayjs(params[0].axisValue).subtract(1, 'month');

                const result = params.map((param, index) => ({
                    value: param.value,
                    date: index > 0 ? prevDate : currentDate,
                    color: param.color
                }));

                let tooltipItem = ``;
                result.forEach((el, index) => {
                    tooltipItem += `<h6 class="fs-9 text-body-tertiary ${
        index > 0 && 'mb-0'
      }"><span class="fas fa-circle me-2" style="color:${el.color}"></span>
      ${el.date.format('MMM DD')} : ${el.value}
    </h6>`;
                });
                return `<div class='ms-1'>
              ${tooltipItem}
            </div>`;
            };

            if ($echartNewCustomersTrackiCharts) {
                const userOptions = getData($echartNewCustomersTrackiCharts, 'echarts');
                const chart = window.echarts.init($echartNewCustomersTrackiCharts);
                const getDefaultOptions = () => ({
                    tooltip: {
                        trigger: 'axis',
                        padding: 10,
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        axisPointer: {
                            type: 'none'
                        },
                        formatter: tooltipFormatter
                    },
                    xAxis: [{
                            type: 'category',
                            data: getDates(
                                new Date('5/1/2022'),
                                new Date('5/7/2022'),
                                1000 * 60 * 60 * 24
                            ),
                            show: true,
                            boundaryGap: false,
                            axisLine: {
                                show: true,
                                lineStyle: {
                                    color: getColor('secondary-bg')
                                }
                            },
                            axisTick: {
                                show: false
                            },
                            axisLabel: {
                                formatter: value => window.dayjs(value).format('DD MMM'),
                                showMinLabel: true,
                                showMaxLabel: false,
                                color: getColor('secondary-color'),
                                align: 'left',
                                interval: 5,
                                fontFamily: 'Nunito Sans',
                                fontWeight: 600,
                                fontSize: 12.8
                            }
                        },
                        {
                            type: 'category',
                            position: 'bottom',
                            show: true,
                            data: getDates(
                                new Date('5/1/2022'),
                                new Date('5/7/2022'),
                                1000 * 60 * 60 * 24
                            ),
                            axisLabel: {
                                formatter: value => window.dayjs(value).format('DD MMM'),
                                interval: 130,
                                showMaxLabel: true,
                                showMinLabel: false,
                                color: getColor('secondary-color'),
                                align: 'right',
                                fontFamily: 'Nunito Sans',
                                fontWeight: 600,
                                fontSize: 12.8
                            },
                            axisLine: {
                                show: false
                            },
                            axisTick: {
                                show: false
                            },
                            splitLine: {
                                show: false
                            },
                            boundaryGap: false
                        }
                    ],
                    yAxis: {
                        show: false,
                        type: 'value',
                        boundaryGap: false
                    },
                    series: [{
                            type: 'line',
                            data: [150, 100, 300, 200, 250, 180, 250],
                            showSymbol: false,
                            symbol: 'circle',
                            lineStyle: {
                                width: 2,
                                color: getColor('secondary-bg')
                            },
                            emphasis: {
                                lineStyle: {
                                    color: getColor('secondary-bg')
                                }
                            }
                        },
                        {
                            type: 'line',
                            data: [200, 150, 250, 100, 500, 400, 600],
                            lineStyle: {
                                width: 2,
                                color: getColor('primary')
                            },
                            showSymbol: false,
                            symbol: 'circle'
                        }
                    ],
                    grid: {
                        left: 0,
                        right: 0,
                        top: 5,
                        bottom: 20
                    }
                });
                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };


                /* -------------------------------------------------------------------------- */
        /*                             Echarts Project Status pie                     */
        /* -------------------------------------------------------------------------- */
        const todoStatuspieLabelAlignChartInit = () => {
            const {
                getColor,
                getData,
                rgbaColor
            } = window.phoenix.utils;
            const $chartEl = document.querySelector(
                '.echart-todo-status-pie-label-align-chart'
            );

            const data = <?php echo json_encode($todo_status_chart); ?>

            if ($chartEl) {
                const userOptions = getData($chartEl, 'echarts');
                const chart = window.echarts.init($chartEl);
                const getDefaultOptions = () => ({
                    title: [{
                            text: 'Pie Label Align Chart',
                            left: 'center',
                            textStyle: {
                                color: getColor('tertiary-color')
                            }
                        },
                        {
                            subtext: 'Project status',
                            left: '50%',
                            top: '85%',
                            textAlign: 'center',
                            subtextStyle: {
                                color: getColor('tertiary-color')
                            }
                        }
                    ],

                    tooltip: {
                        trigger: 'item',
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        axisPointer: {
                            type: 'none'
                        },
                        position(pos, ...size) {
                            if (window.innerWidth <= 540) {
                                const tooltipHeight = size[1].offsetHeight;
                                const obj = {
                                    top: pos[1] - tooltipHeight - 20
                                };
                                obj[pos[0] < size[3].viewSize[0] / 2 ? 'left' : 'right'] = 5;
                                return obj;
                            }
                            return null;
                        }
                    },

                    series: [{
                        type: 'pie',
                        radius: window.innerWidth < 530 ? '45%' : '60%',
                        center: ['50%', '50%'],
                        data,
                        label: {
                            position: 'outer',
                            alignTo: 'labelLine',
                            bleedMargin: 5,
                            color: getColor('tertiary-color')
                        },
                        left: '5%',
                        right: '5%',
                        top: 0,
                        bottom: 0
                    }]
                });

                const responsiveOptions = {
                    xs: {
                        series: [{
                            radius: '45%'
                        }]
                    },
                    sm: {
                        series: [{
                            radius: '60%'
                        }]
                    }
                };

                echartSetOption(chart, userOptions, getDefaultOptions, responsiveOptions);
            }
        };

        /* -------------------------------------------------------------------------- */
        /*                             Echarts Project Status pie                     */
        /* -------------------------------------------------------------------------- */
        const projectStatuspieLabelAlignChartInit = () => {
            const {
                getColor,
                getData,
                rgbaColor
            } = window.phoenix.utils;
            const $chartEl = document.querySelector(
                '.echart-project-status-pie-label-align-chart'
            );

            const data = <?php echo json_encode($project_status_chart); ?>
            // const data = [{
            //         value: 850,
            //         name: 'Starter',
            //         itemStyle: {
            //             color: rgbaColor(getColor('primary'), 0.5)
            //         }
            //     },
            //     {
            //         value: 750,
            //         name: 'Starter Pro',
            //         itemStyle: {
            //             color: getColor('danger')
            //         }
            //     },
            //     {
            //         value: 457,
            //         name: 'Basic',
            //         itemStyle: {
            //             color: getColor('primary')
            //         }
            //     },
            //     {
            //         value: 654,
            //         name: 'Optimal',
            //         itemStyle: {
            //             color: getColor('secondary')
            //         }
            //     },
            //     {
            //         value: 447,
            //         name: 'Business',
            //         itemStyle: {
            //             color: getColor('warning')
            //         }
            //     },
            //     {
            //         value: 682,
            //         name: 'Classic addition',
            //         itemStyle: {
            //             color: rgbaColor(getColor('warning'), 0.8)
            //         }
            //     },
            //     {
            //         value: 471,
            //         name: 'Premium',
            //         itemStyle: {
            //             color: getColor('success')
            //         }
            //     },
            //     {
            //         value: 524,
            //         name: 'Platinum',
            //         itemStyle: {
            //             color: getColor('info')
            //         }
            //     },
            //     {
            //         value: 200,
            //         name: 'Platinum Pro',
            //         itemStyle: {
            //             color: rgbaColor(getColor('primary'), 0.5)
            //         }
            //     }
            // ];

            if ($chartEl) {
                const userOptions = getData($chartEl, 'echarts');
                const chart = window.echarts.init($chartEl);
                const getDefaultOptions = () => ({
                    title: [{
                            text: 'Pie Label Align Chart',
                            left: 'center',
                            textStyle: {
                                color: getColor('tertiary-color')
                            }
                        },
                        {
                            subtext: 'Project status',
                            left: '50%',
                            top: '85%',
                            textAlign: 'center',
                            subtextStyle: {
                                color: getColor('tertiary-color')
                            }
                        }
                    ],

                    tooltip: {
                        trigger: 'item',
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        axisPointer: {
                            type: 'none'
                        },
                        position(pos, ...size) {
                            if (window.innerWidth <= 540) {
                                const tooltipHeight = size[1].offsetHeight;
                                const obj = {
                                    top: pos[1] - tooltipHeight - 20
                                };
                                obj[pos[0] < size[3].viewSize[0] / 2 ? 'left' : 'right'] = 5;
                                return obj;
                            }
                            return null;
                        }
                    },

                    series: [{
                        type: 'pie',
                        radius: window.innerWidth < 530 ? '45%' : '60%',
                        center: ['50%', '50%'],
                        data,
                        label: {
                            position: 'outer',
                            alignTo: 'labelLine',
                            bleedMargin: 5,
                            color: getColor('tertiary-color')
                        },
                        left: '5%',
                        right: '5%',
                        top: 0,
                        bottom: 0
                    }]
                });

                const responsiveOptions = {
                    xs: {
                        series: [{
                            radius: '45%'
                        }]
                    },
                    sm: {
                        series: [{
                            radius: '60%'
                        }]
                    }
                };

                echartSetOption(chart, userOptions, getDefaultOptions, responsiveOptions);
            }
        };

        /* -------------------------------------------------------------------------- */
        /*                             Echarts Total Sales by month                   */
        /* -------------------------------------------------------------------------- */

        const totalSalesChartInit = () => {
            const {
                getColor,
                getData,
                getDates
            } = window.phoenix.utils;
            const $totalSalesChart = document.querySelector('.echart-total-sales-by-month-chart');

            // getItemFromStore('phoenixTheme')

            const dates = getDates(
                new Date('5/1/2022'),
                new Date('5/30/2022'),
                1000 * 60 * 60 * 24
            );

            const months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];

            const currentMonthData = [
                100, 200, 300, 300, 300, 250, 200, 200, 200, 200, 200, 500, 500, 500, 600,
                700, 800, 900, 1000, 1100, 850, 600, 600, 600, 400, 200, 200, 300, 300, 300
            ];

            const prevMonthData = [
                200, 200, 100, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 200, 400, 600,
                600, 600, 800, 1000, 700, 400, 450, 500, 600, 700, 650, 600, 550
            ];

            const tooltipFormatter = params => {
                const currentDate = window.dayjs(params[0].axisValue);
                const prevDate = window.dayjs(params[0].axisValue).subtract(1, 'month');

                const result = params.map((param, index) => ({
                    value: param.value,
                    date: index > 0 ? prevDate : currentDate,
                    color: param.color
                }));

                let tooltipItem = ``;
                result.forEach((el, index) => {
                    tooltipItem += `<h6 class="fs-9 text-body-tertiary ${
        index > 0 && 'mb-0'
      }"><span class="fas fa-circle me-2" style="color:${el.color}"></span>
      ${el.date.format('MMM DD')} : ${el.value}
    </h6>`;
                });
                return `<div class='ms-1'>
              ${tooltipItem}
            </div>`;
            };

            if ($totalSalesChart) {
                const userOptions = getData($totalSalesChart, 'echarts');
                const chart = window.echarts.init($totalSalesChart);

                const getDefaultOptions = () => ({
                    color: [getColor('primary'), getColor('info')],
                    tooltip: {
                        trigger: 'axis',
                        padding: 10,
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        axisPointer: {
                            type: 'none'
                        },
                        formatter: tooltipFormatter
                    },
                    xAxis: {
                        type: 'category',
                        data: months,
                        axisLine: {
                            lineStyle: {
                                color: getColor('tertiary-bg'),
                                type: 'solid'
                            }
                        },
                        axisTick: {
                            show: true
                        },
                        axisLabel: {
                            color: getColor('quaternary-color'),
                            formatter: value => value.substring(0, 3),
                            margin: 15
                        },
                        splitLine: {
                            show: false
                        }
                    },
                    xAxis: [{
                            type: 'category',
                            data: months,
                            axisLabel: {
                                // formatter: value => window.dayjs(value).format('DD MMM'),
                                formatter: value => value.substring(0, 3),

                                // interval: 13,
                                // showMinLabel: true,
                                // showMaxLabel: false,
                                color: getColor('secondary-color'),
                                align: 'left',
                                fontFamily: 'Nunito Sans',
                                fontWeight: 600,
                                fontSize: 12.8
                            },
                            axisLine: {
                                show: true,
                                lineStyle: {
                                    color: getColor('secondary-bg')
                                }
                            },
                            axisTick: {
                                show: true
                            },
                            splitLine: {
                                show: true,
                                interval: 0,
                                lineStyle: {
                                    color: window.config.config.phoenixTheme === 'dark' ?
                                        getColor('body-highlight-bg') : getColor('secondary-bg')
                                }
                            },
                            boundaryGap: false
                        },
                        {
                            type: 'category',
                            position: 'bottom',
                            data: months,
                            axisLabel: {
                                // formatter: value => window.dayjs(value).format('DD MMM'),
                                formatter: value => value.substring(0, 3),

                                interval: 130,
                                showMaxLabel: true,
                                showMinLabel: false,
                                color: getColor('secondary-color'),
                                align: 'right',
                                fontFamily: 'Nunito Sans',
                                fontWeight: 600,
                                fontSize: 12.8
                            },
                            axisLine: {
                                show: false
                            },
                            axisTick: {
                                show: false
                            },
                            splitLine: {
                                show: false
                            },
                            boundaryGap: false
                        }
                    ],
                    yAxis: {
                        position: 'right',
                        axisPointer: {
                            type: 'none'
                        },
                        axisTick: 'none',
                        splitLine: {
                            show: false
                        },
                        axisLine: {
                            show: false
                        },
                        axisLabel: {
                            show: false
                        }
                    },
                    series: [{
                            name: 'd',
                            type: 'line',
                            // data: Array.from(Array(30).keys()).map(() =>
                            //   getRandomNumber(100, 300)
                            // ),
                            data: currentMonthData,
                            showSymbol: false,
                            symbol: 'circle',
                            zlevel: 2
                        },
                        {
                            name: 'e',
                            type: 'line',
                            // data: Array.from(Array(30).keys()).map(() =>
                            //   getRandomNumber(100, 300)
                            // ),
                            data: prevMonthData,
                            // symbol: 'none',
                            lineStyle: {
                                type: 'dashed',
                                width: 1,
                                color: getColor('info')
                            },
                            showSymbol: false,
                            symbol: 'circle',
                            zlevel: 1
                        }
                    ],
                    grid: {
                        right: 2,
                        left: 5,
                        bottom: '20px',
                        top: '2%',
                        containLabel: false
                    },
                    animation: false
                });
                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        /* -------------------------------------------------------------------------- */
        /*                             Echarts Total Sales by month 2                   */
        /* -------------------------------------------------------------------------- */
        const {
            echarts: echarts$1
        } = window;
        const totalSalesByMonthChartInit = () => {
            const {
                getColor,
                getData
            } = window.phoenix.utils;

            const $totalSalesByMonthChart = document.querySelector(
                '.echart-total-sales-by-month'
            );

            const months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];

            const data = <?php echo json_encode($total_sales_by_month); ?>;

            console.log(data);

            if ($totalSalesByMonthChart) {
                const userOptions = getData($totalSalesByMonthChart, 'echarts');
                const chart = echarts$1.init($totalSalesByMonthChart);
                const getDefaultOptions = () => ({
                    color: getColor('body-highlight-bg'),
                    legend: {
                        data: [{
                            name: 'Sales',
                            icon: 'roundRect',
                            itemStyle: {
                                color: getColor('primary-light'),
                                borderWidth: 0
                            }
                        }, ],

                        right: 'right',
                        width: '100%',
                        itemWidth: 16,
                        itemHeight: 8,
                        itemGap: 20,
                        top: 3,
                        inactiveColor: getColor('quaternary-color'),
                        inactiveBorderWidth: 0,
                        textStyle: {
                            color: getColor('body-color'),
                            fontWeight: 600,
                            fontFamily: 'Nunito Sans'
                        }
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'none'
                        },
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        formatter: tooltipFormatter
                    },
                    xAxis: {
                        type: 'category',
                        data: months,
                        show: true,
                        boundaryGap: false,
                        axisLine: {
                            show: true,
                            lineStyle: {
                                color: getColor('tertiary-bg')
                            }
                        },
                        axisTick: {
                            show: false
                        },
                        axisLabel: {
                            // interval: 1,
                            showMinLabel: false,
                            showMaxLabel: false,
                            color: getColor('secondary-color'),
                            formatter: value => value.slice(0, 3),
                            fontFamily: 'Nunito Sans',
                            fontWeight: 600,
                            fontSize: 12.8
                        },
                        splitLine: {
                            show: true,
                            lineStyle: {
                                color: getColor('secondary-bg'),
                                type: 'dashed'
                            }
                        }
                    },
                    yAxis: {
                        type: 'value',
                        boundaryGap: false,
                        axisLabel: {
                            showMinLabel: true,
                            showMaxLabel: true,
                            color: getColor('secondary-color'),
                            formatter: value => `${value}`,
                            fontFamily: 'Nunito Sans',
                            fontWeight: 600,
                            fontSize: 12.8
                        },
                        splitLine: {
                            show: true,
                            lineStyle: {
                                color: getColor('secondary-bg')
                            }
                        }
                    },
                    series: [{
                            name: 'Sales',
                            type: 'line',
                            data: data,
                            showSymbol: false,
                            symbol: 'circle',
                            symbolSize: 10,
                            emphasis: {
                                lineStyle: {
                                    width: 1
                                }
                            },
                            lineStyle: {
                                // type: 'dashed',
                                width: 1,
                                color: getColor('primary-light')
                            },
                            itemStyle: {
                                borderColor: getColor('primary-light'),
                                borderWidth: 3
                            },
                            zlevel: 3
                        },
                        // {
                        //     name: 'Third time',
                        //     type: 'line',
                        //     data: [50, 50, 30, 62, 18, 70, 70, 22, 70, 70, 70, 70],
                        //     showSymbol: false,
                        //     symbol: 'circle',
                        //     symbolSize: 10,
                        //     emphasis: {
                        //         lineStyle: {
                        //             width: 1
                        //         }
                        //     },
                        //     lineStyle: {
                        //         width: 1,
                        //         color: getColor('info-lighter')
                        //     },
                        //     itemStyle: {
                        //         borderColor: getColor('info-lighter'),
                        //         borderWidth: 3
                        //     },
                        //     zlevel: 2
                        // },
                        // {
                        //     name: 'Second time',
                        //     type: 'line',
                        //     data: [40, 78, 60, 78, 60, 20, 60, 40, 60, 40, 20, 78],
                        //     showSymbol: false,
                        //     symbol: 'circle',
                        //     symbolSize: 10,
                        //     emphasis: {
                        //         lineStyle: {
                        //             width: 3
                        //         }
                        //     },
                        //     lineStyle: {
                        //         width: 3,
                        //         color: getColor('primary')
                        //     },
                        //     itemStyle: {
                        //         borderColor: getColor('primary'),
                        //         borderWidth: 3
                        //     },
                        //     zlevel: 1
                        // }
                    ],
                    grid: {
                        left: 0,
                        right: 8,
                        top: '14%',
                        bottom: 0,
                        containLabel: true
                    }
                });
                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        const {
            echarts
        } = window;

        /* -------------------------------------------------------------------------- */
        /*                             Echarts Total Projects by month                */
        /* -------------------------------------------------------------------------- */

        const totalProjectByMonthChartInit = () => {
            const {
                getColor,
                getData,
                getDates
            } = window.phoenix.utils;
            const totalOrdersChartEl = document.querySelector('.echart-total-project-by-month');

            if (totalOrdersChartEl) {
                const userOptions = getData(totalOrdersChartEl, 'echarts');
                const chart = window.echarts.init(totalOrdersChartEl);
                const months = [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'
                ];

                const data = <?php echo json_encode($projects_by_month); ?>;

                const getDefaultOptions = () => ({
                    color: getColor('primary'),
                    tooltip: {
                        trigger: 'item',
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        position: (...params) => handleTooltipPosition(params),
                        borderWidth: 1,
                        transitionDuration: 0,
                        //   formatter: params => {
                        //     return `<strong>${window
                        //     .dayjs(params.name)
                        //     .format('DD MMM')}:</strong> ${params.value}`;
                        //   },
                    },
                    xAxis: {
                        type: 'category',
                        //   data: getDates(
                        //     new Date('5/1/2022'),
                        //     new Date('5/7/2022'),
                        //     1000 * 60 * 60 * 24
                        //   ),
                        data: months,
                        show: true,
                        boundaryGap: false,
                        axisLine: {
                            show: true,
                            lineStyle: {
                                color: getColor('secondary-bg')
                            }
                        },
                        axisTick: {
                            show: false
                        },
                        axisLabel: {
                            formatter: value => window.dayjs(value).format('DD MMM'),
                            interval: 6,
                            showMinLabel: true,
                            showMaxLabel: true,
                            color: getColor('secondary-color')
                        }
                    },
                    yAxis: {
                        show: false,
                        type: 'value',
                        boundaryGap: false
                    },
                    series: [{
                        type: 'bar',
                        barWidth: '5px',
                        data: data,
                        showBackground: true,
                        symbol: 'none',
                        itemStyle: {
                            borderRadius: 10
                        },
                        backgroundStyle: {
                            borderRadius: 10,
                            color: getColor('primary-bg-subtle')
                        }
                    }],
                    grid: {
                        right: 10,
                        left: 10,
                        bottom: 0,
                        top: 0
                    }
                });

                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        /* -------------------------------------------------------------------------- */
        /*                             Echarts Total Projects by month                */
        /* -------------------------------------------------------------------------- */

        const spendByDepartmenttInit = () => {
            const {
                getColor,
                getData,
                toggleColor
            } = window.phoenix.utils;
            const spendByDepartment = document.querySelector('.echart-spend-by-department');

            if (spendByDepartment) {
                const userOptions = getData(spendByDepartment, 'echarts');
                const chart = window.echarts.init(spendByDepartment);

                const getDefaultOptions = () => ({
                    color: [
                        toggleColor(getColor('info-light'), getColor('info-dark')),
                        toggleColor(getColor('warning-light'), getColor('warning-dark')),
                        toggleColor(getColor('danger-light'), getColor('danger-dark')),
                        toggleColor(getColor('success-light'), getColor('success-dark')),
                        getColor('primary')
                    ],
                    tooltip: {
                        trigger: 'item',
                        position: (...params) => handleTooltipPosition(params)
                    },
                    responsive: true,
                    maintainAspectRatio: false,

                    series: [{
                        name: 'Departments budget utiliztion',
                        type: 'pie',
                        radius: ['48%', '90%'],
                        startAngle: 30,
                        avoidLabelOverlap: false,
                        // label: {
                        //   show: false,
                        //   position: 'center'
                        // },

                        label: {
                            show: false,
                            position: 'center',
                            formatter: '{x|{d}%} \n {y|{b}}',
                            rich: {
                                x: {
                                    fontSize: 31.25,
                                    fontWeight: 800,
                                    color: getColor('tertiary-color'),
                                    padding: [0, 0, 5, 15]
                                },
                                y: {
                                    fontSize: 12.8,
                                    color: getColor('tertiary-color'),
                                    fontWeight: 600
                                }
                            }
                        },
                        emphasis: {
                            label: {
                                show: true
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: <?php echo json_encode($total_spent_by_department); ?>
                        //  [
                        //   { value: 78, name: 'Product design' },
                        //   { value: 63, name: 'Development' },
                        //   { value: 56, name: 'QA & Testing' },
                        //   { value: 36, name: 'Customer queries' },
                        //   { value: 24, name: 'R & D' }
                        // ]
                    }],
                    grid: {
                        bottom: 0,
                        top: 0,
                        left: 0,
                        right: 0,
                        containLabel: false
                    }
                });

                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        /* -------------------------------------------------------------------------- */
        /*                             Echarts Total Sales                            */
        /* -------------------------------------------------------------------------- */

        const projectionVsActualChartTrackiInit = () => {
            const {
                getColor,
                getData,
                getPastDates
            } = window.phoenix.utils;
            const $projectionVsActualChartEl = document.querySelector(
                '.echart-projection-actual'
            );

            const dates = getPastDates(15);

            const data1 = [
                44485, 20428, 47302, 45180, 31034, 46358, 26581, 36628, 38219, 43256
            ];

            const data2 = [
                38911, 29452, 31894, 47876, 31302, 27731, 25490, 30355, 27176, 30393
            ];

            if ($projectionVsActualChartEl) {
                const userOptions = getData($projectionVsActualChartEl, 'echarts');
                const chart = window.echarts.init($projectionVsActualChartEl);

                const getDefaultOptions = () => ({
                    color: [getColor('primary'), getColor('tertiary-bg')],
                    tooltip: {
                        trigger: 'axis',
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        axisPointer: {
                            type: 'none'
                        },
                        position: (...params) => handleTooltipPosition(params),
                        formatter: params => tooltipFormatter(params)
                    },
                    legend: {
                        data: ['Projected revenue', 'Actual revenue'],
                        right: 'right',
                        width: '100%',
                        itemWidth: 16,
                        itemHeight: 8,
                        itemGap: 20,
                        top: 3,
                        inactiveColor: getColor('quaternary-color'),
                        textStyle: {
                            color: getColor('body-color'),
                            fontWeight: 600,
                            fontFamily: 'Nunito Sans'
                            // fontSize: '12.8px'
                        }
                    },
                    xAxis: {
                        type: 'category',
                        // boundaryGap: false,
                        axisLabel: {
                            color: getColor('secondary-color'),
                            formatter: value => window.dayjs(value).format('MMM DD'),
                            interval: 3,
                            fontFamily: 'Nunito Sans',
                            fontWeight: 600,
                            fontSize: 12.8
                        },
                        data: dates,
                        axisLine: {
                            lineStyle: {
                                color: getColor('tertiary-bg')
                            }
                        },
                        axisTick: false
                    },
                    yAxis: {
                        axisPointer: {
                            type: 'none'
                        },
                        // boundaryGap: false,
                        axisTick: 'none',
                        splitLine: {
                            interval: 5,
                            lineStyle: {
                                color: getColor('secondary-bg')
                            }
                        },
                        axisLine: {
                            show: false
                        },
                        axisLabel: {
                            fontFamily: 'Nunito Sans',
                            fontWeight: 600,
                            fontSize: 12.8,
                            color: getColor('secondary-color'),
                            margin: 20,
                            verticalAlign: 'bottom',
                            formatter: value => `$${value.toLocaleString()}`
                        }
                    },
                    series: [{
                            name: 'Projected revenue',
                            type: 'bar',
                            barWidth: '6px',
                            data: data2,
                            barGap: '30%',
                            label: {
                                show: false
                            },
                            itemStyle: {
                                borderRadius: [2, 2, 0, 0],
                                color: getColor('primary')
                            }
                        },
                        {
                            name: 'Actual revenue',
                            type: 'bar',
                            data: data1,
                            barWidth: '6px',
                            barGap: '30%',
                            label: {
                                show: false
                            },
                            z: 10,
                            itemStyle: {
                                borderRadius: [2, 2, 0, 0],
                                color: getColor('info-bg-subtle')
                            }
                        }
                    ],
                    grid: {
                        right: 0,
                        left: 3,
                        bottom: 0,
                        top: '15%',
                        containLabel: true
                    },
                    animation: false
                });

                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        /* -------------------------------------------------------------------------- */
        /*                             Echarts Completed Projects by month                */
        /* -------------------------------------------------------------------------- */

        const totalOrdersChartInitBig = () => {
            const {
                getColor,
                getData
            } = window.phoenix.utils;
            const $chartEl = document.querySelector('.echart-completed-projects-by-month');
            // const $chartEl = document.querySelector('.echart-total-orders');



            const months = [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ];
            const data = <?php echo json_encode($completed_projects_by_month); ?>;
            // [
            //   1020, 1160, 1300, 958, 1240, 1020, 1409, 1200, 1051, 1120, 1240, 1054
            // ];

            if ($chartEl) {
                const userOptions = getData($chartEl, 'echarts');
                const chart = window.echarts.init($chartEl);
                const getDefaultOptions = () => ({
                    tooltip: {
                        trigger: 'axis',
                        padding: [7, 10],
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        formatter: params => tooltipFormatter(params),
                        transitionDuration: 0,
                        axisPointer: {
                            type: 'none'
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: months,
                        axisLine: {
                            lineStyle: {
                                color: getColor('tertiary-bg'),
                                type: 'solid'
                            }
                        },
                        axisTick: {
                            show: true
                        },
                        axisLabel: {
                            color: getColor('quaternary-color'),
                            formatter: value => value.substring(0, 3),
                            margin: 15
                        },
                        splitLine: {
                            show: false
                        }
                    },
                    yAxis: {
                        type: 'value',
                        boundaryGap: false,
                        axisLabel: {
                            show: true,
                            color: getColor('quaternary-color'),
                            margin: 15
                        },
                        splitLine: {
                            show: true,
                            lineStyle: {
                                color: getColor('secondary-bg')
                            }
                        },
                        axisTick: {
                            show: true
                        },
                        axisLine: {
                            show: true
                        },
                        min: 0,
                        // max: 20
                    },
                    series: [{
                        type: 'bar',
                        name: 'Total',
                        data,
                        lineStyle: {
                            color: getColor('primary')
                        },
                        itemStyle: {
                            color: getColor('primary'),
                            barBorderRadius: [3, 3, 0, 0]
                        },
                        showSymbol: false,
                        symbol: 'circle',
                        smooth: false,
                        hoverAnimation: true
                    }],
                    grid: {
                        right: '3%',
                        left: '10%',
                        bottom: '10%',
                        top: '5%'
                    }
                });
                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        const completedTaskChartInit = () => {
            const {
                getColor,
                getData,
                getDates
            } = window.phoenix.utils;

            const $totalSalesChart = document.querySelector(
                '.echart-completed-task-chart'
            );

            const dates = getDates(
                new Date('5/1/2022'),
                new Date('5/30/2022'),
                1000 * 60 * 60 * 24
            );

            const currentMonthData = [
                50, 115, 180, 180, 180, 150, 120, 120, 120, 120, 120, 240, 240, 240, 240,
                270, 300, 330, 360, 390, 340, 290, 310, 330, 350, 320, 290, 330, 370, 350
            ];

            const prevMonthData = [
                130, 130, 130, 90, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 110, 170,
                230, 230, 230, 270, 310, 270, 230, 260, 290, 320, 280, 280, 280
            ];
            const tooltipFormatter = params => {
                const currentDate = window.dayjs(params[0].axisValue);
                const prevDate = window.dayjs(params[0].axisValue).subtract(1, 'month');

                const result = params.map((param, index) => ({
                    value: param.value,
                    date: index > 0 ? prevDate : currentDate,
                    color: param.color
                }));

                let tooltipItem = ``;
                result.forEach((el, index) => {
                    tooltipItem += `<h6 class="fs-9 text-body-tertiary ${
        index > 0 && 'mb-0'
      }"><span class="fas fa-circle me-2" style="color:${el.color}"></span>
      ${el.date.format('MMM DD')} : ${el.value}
    </h6>`;
                });
                return `<div class='ms-1'>
              ${tooltipItem}
            </div>`;
            };

            if ($totalSalesChart) {
                const userOptions = getData($totalSalesChart, 'echarts');
                const chart = window.echarts.init($totalSalesChart);

                const getDefaultOptions = () => ({
                    color: [getColor('primary'), getColor('info')],
                    tooltip: {
                        trigger: 'axis',
                        padding: 10,
                        backgroundColor: getColor('body-highlight-bg'),
                        borderColor: getColor('border-color'),
                        textStyle: {
                            color: getColor('light-text-emphasis')
                        },
                        borderWidth: 1,
                        transitionDuration: 0,
                        axisPointer: {
                            type: 'none'
                        },
                        formatter: tooltipFormatter
                    },
                    xAxis: [{
                            type: 'category',
                            data: dates,
                            axisLabel: {
                                formatter: value => window.dayjs(value).format('DD MMM'),
                                interval: 13,
                                showMinLabel: true,
                                showMaxLabel: false,
                                color: getColor('secondary-color'),
                                align: 'left',
                                fontFamily: 'Nunito Sans',
                                fontWeight: 600,
                                fontSize: 12.8
                            },
                            axisLine: {
                                show: true,
                                lineStyle: {
                                    color: getColor('secondary-bg')
                                }
                            },
                            axisTick: {
                                show: false
                            },
                            splitLine: {
                                show: true,
                                interval: 0,
                                lineStyle: {
                                    color: getColor('secondary-bg')
                                }
                            },
                            boundaryGap: false
                        },
                        {
                            type: 'category',
                            position: 'bottom',
                            data: dates,
                            axisLabel: {
                                formatter: value => window.dayjs(value).format('DD MMM'),
                                interval: 130,
                                showMaxLabel: true,
                                showMinLabel: false,
                                color: getColor('secondary-color'),
                                align: 'right',
                                fontFamily: 'Nunito Sans',
                                fontWeight: 600,
                                fontSize: 12.8
                            },
                            axisLine: {
                                show: false
                            },
                            axisTick: {
                                show: false
                            },
                            splitLine: {
                                show: false
                            },
                            boundaryGap: false
                        }
                    ],
                    yAxis: {
                        position: 'right',
                        axisPointer: {
                            type: 'none'
                        },
                        axisTick: 'none',
                        splitLine: {
                            show: false
                        },
                        axisLine: {
                            show: false
                        },
                        axisLabel: {
                            show: false
                        }
                    },
                    series: [{
                            name: 'd',
                            type: 'line',
                            // data: Array.from(Array(30).keys()).map(() =>
                            //   getRandomNumber(100, 300)
                            // ),
                            data: currentMonthData,
                            showSymbol: false,
                            symbol: 'circle'
                        },
                        {
                            name: 'e',
                            type: 'line',
                            // data: Array.from(Array(30).keys()).map(() =>
                            //   getRandomNumber(100, 300)
                            // ),
                            data: prevMonthData,
                            // symbol: 'none',
                            lineStyle: {
                                type: 'dashed',
                                width: 1,
                                color: getColor('info')
                            },
                            showSymbol: false,
                            symbol: 'circle'
                        }
                    ],
                    grid: {
                        right: 2,
                        left: 5,
                        bottom: '20px',
                        top: '2%',
                        containLabel: false
                    },
                    animation: false
                });

                echartSetOption(chart, userOptions, getDefaultOptions);
            }
        };

        const {
            docReady
        } = window.phoenix.utils;
        docReady(budgetUtilizationtInit);
        docReady(projectStatuspieLabelAlignChartInit);
        docReady(todoStatuspieLabelAlignChartInit);
        docReady(spendByDepartmenttInit);
        docReady(totalOrdersChartInitBig);
        docReady(totalProjectByMonthChartInit);
        docReady(totalSalesChartInit);
        docReady(totalSalesByMonthChartInit);
        docReady(newCustomersChartsTrackiInit);
        docReady(topCouponsChartInit);
        docReady(projectionVsActualChartTrackiInit);
        docReady(completedTaskChartInit);

    }));
</script>
