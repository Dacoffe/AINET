@extends('layouts.app')
@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <!-- Cabeçalho -->
        <div class="bg-blue-900 rounded-t-lg px-6 py-4">
            <h2 class="text-xl font-bold text-white">Dashboard</h2>
        </div>

        @include('partials.admin.menu-choice')
        <div class="mx-auto max-w-5xl px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- LEFT COLUMN: Users Info --}}
                <div class="lg:w-1/3 w-full">

                    {{-- Members Pie Chart --}}
                    <div class="bg-white rounded shadow-sm p-2 flex justify-center">
                        <canvas id="membersChart" width="180" height="180"></canvas>
                    </div>

                </div>

                {{-- Members Info --}}

                {{-- RIGHT COLUMN: Charts --}}
                <div class="lg:w-2/3 w-full flex flex-col justify-center">
                    {{-- Monthly Sales Chart --}}
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow-sm p-4 flex justify-center">
                            <canvas id="salesChart" width="auto" height="auto"></canvas>
                        </div>
                    </div>

                    {{-- Monthly Product Sales Chart --}}
                    <div class="mb-8">
                        <div class="bg-white rounded-lg shadow-sm p-4 flex justify-center">
                            <canvas id="productSalesChart" width="auto" height="auto"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Members Pie Chart
            const membersCtx = document.getElementById('membersChart').getContext('2d');
            new Chart(membersCtx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [
                            {{ $numberOfBoardMembers }},
                            {{ $numberOfEmployees }},
                            {{ $numberOfMembers }}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }],
                    labels: ['Board', 'Employees', 'Members']
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Members by Type',
                            font: {
                                size: 18
                            }
                        }
                    }
                }
            });

            // Monthly Sales Chart
            document.addEventListener('DOMContentLoaded', function() {
                const salesCtx = document.getElementById('salesChart')?.getContext('2d');
                if (!salesCtx) return;

                const monthlyData = @json($monthlyOrderStats ?? []);
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                new Chart(salesCtx, {
                    type: 'bar',
                    data: {
                        labels: monthNames,
                        datasets: [{
                                label: 'Completed',
                                data: monthlyData.map(item => item.completed || 0),
                                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Pending',
                                data: monthlyData.map(item => item.pending || 0),
                                backgroundColor: 'rgba(255, 206, 86, 0.7)',
                                borderColor: 'rgba(255, 206, 86, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Cancelled',
                                data: monthlyData.map(item => item.canceled || 0),
                                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Monthly Orders ({{ $currentYear ?? date('Y') }})',
                                font: {
                                    size: 18
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    footer: (tooltipItems) => {
                                        const month = tooltipItems[0]?.dataIndex;
                                        const total = monthlyData[month]?.total_orders || 0;
                                        return `Total: ${total}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true
                            }
                        }
                    }
                });
            });

            // Monthly Product Sales Chart
            const productSalesCtx = document.getElementById('productSalesChart')?.getContext('2d');
            if (productSalesCtx) {
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const productSalesData = @json($allMonthsProductSales ?? array_fill(0, 12, 0));

                new Chart(productSalesCtx, {
                    type: 'line',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Sales',
                            data: productSalesData,
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Sales per Month ({{ $currentYear ?? date('Y') }})',
                                font: {
                                    size: 18
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.parsed.y}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Sales (€/EUR)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endsection
