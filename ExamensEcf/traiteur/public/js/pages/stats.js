// Récupération des données depuis le HTML
const labels = JSON.parse(document.getElementById('stats-labels').dataset.labels);
const data = JSON.parse(document.getElementById('stats-data').dataset.data);

// Graphique
new Chart(document.getElementById('graphique'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Nombre de commandes',
            data: data,
            backgroundColor: 'rgba(201, 168, 76, 0.4)',
            borderColor: 'rgba(201, 168, 76, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: '#f5f5f5' },
                grid: { color: '#2d2d2d' }
            },
            x: {
                ticks: { color: '#f5f5f5' },
                grid: { color: '#2d2d2d' }
            }
        },
        plugins: {
            legend: {
                labels: { color: '#f5f5f5' }
            }
        }
    }
});