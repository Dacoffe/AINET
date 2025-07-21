{{-- filepath: /Applications/ESTG/SecondSimester/2Ano/AINET/Projeto/resources/views/livewire/charts.blade.php --}}
<div>
    <canvas id="salesChart"></canvas>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartData = @json($data);

        new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
            },
        });
    });
</script>
