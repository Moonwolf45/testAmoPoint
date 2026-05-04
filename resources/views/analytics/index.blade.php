<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аналитика посещений</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header .user-info span {
            font-size: 14px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px;
        }

        .controls {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .controls label {
            font-weight: 500;
            font-size: 14px;
            color: #555;
        }

        .controls select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }

        .metric-card:hover {
            transform: translateY(-2px);
        }

        .metric-card .label {
            font-size: 13px;
            color: #888;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #333;
        }

        .metric-card .sub {
            font-size: 12px;
            color: #aaa;
            margin-top: 4px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .chart-card h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .chart-card.full-width {
            grid-column: 1 / -1;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .chart-container.small {
            height: 300px;
        }

        .loading {
            text-align: center;
            padding: 60px;
            color: #888;
            font-size: 16px;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Аналитика посещений</h1>
        <div class="user-info">
            <span>{{ Auth::user()->name ?? 'Администратор' }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn">Выйти</button>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="controls">
            <label for="days">Период:</label>
            <select id="days">
                <option value="1">Сегодня</option>
                <option value="7" selected>7 дней</option>
                <option value="14">14 дней</option>
                <option value="30">30 дней</option>
                <option value="90">90 дней</option>
            </select>
            <button onclick="loadData()" style="padding:8px 16px;background:#667eea;color:white;border:none;border-radius:6px;cursor:pointer;font-size:14px;">Обновить</button>
        </div>

        <div id="loading" class="loading">
            <div class="spinner"></div>
            Загрузка данных...
        </div>

        <div id="dashboard" style="display:none;">
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="label">Всего посещений</div>
                    <div class="value" id="totalVisits">0</div>
                    <div class="sub">за выбранный период</div>
                </div>
                <div class="metric-card">
                    <div class="label">Уникальных посетителей</div>
                    <div class="value" id="uniqueVisitors">0</div>
                    <div class="sub">по IP-адресам</div>
                </div>
                <div class="metric-card">
                    <div class="label">Городов</div>
                    <div class="value" id="citiesCount">0</div>
                    <div class="sub">определено</div>
                </div>
                <div class="metric-card">
                    <div class="label">Среднее в день</div>
                    <div class="value" id="avgPerDay">0</div>
                    <div class="sub">уникальных посещений</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card full-width">
                    <h3>Посещения по часам (уникальные IP)</h3>
                    <div class="chart-container">
                        <canvas id="hourlyChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>Топ-10 городов</h3>
                    <div class="chart-container">
                        <canvas id="cityChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>Посещения по дням</h3>
                    <div class="chart-container">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>По устройствам</h3>
                    <div class="chart-container small">
                        <canvas id="deviceChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>По браузерам</h3>
                    <div class="chart-container small">
                        <canvas id="browserChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <h3>По операционным системам</h3>
                    <div class="chart-container small">
                        <canvas id="osChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let charts = {};

        async function loadData() {
            const days = document.getElementById('days').value;
            document.getElementById('loading').style.display = 'block';
            document.getElementById('dashboard').style.display = 'none';

            try {
                const response = await fetch(`/analytics/data?days=${days}`);
                const data = await response.json();
                renderDashboard(data);
            } catch (error) {
                console.error('Ошибка загрузки данных:', error);
            }
        }

        function renderDashboard(data) {
            // Метрики
            document.getElementById('totalVisits').textContent = data.total_visits.toLocaleString();
            document.getElementById('uniqueVisitors').textContent = data.unique_visitors.toLocaleString();
            document.getElementById('citiesCount').textContent = data.city_data.length;
            const days = parseInt(document.getElementById('days').value);
            const avg = days > 0 ? Math.round(data.unique_visitors / days) : 0;
            document.getElementById('avgPerDay').textContent = avg.toLocaleString();

            // Уничтожаем старые графики
            Object.values(charts).forEach(chart => chart.destroy());
            charts = {};

            // График по часам (бар, ось X - уникальные посещения, ось Y - часы)
            const hours = Array.from({length: 24}, (_, i) => `${String(i).padStart(2, '0')}:00`);
            const hourlyValues = data.hourly_data.map(h => ({
                hour: h.hour,
                visits: h.unique_visits
            }));

            const hourlyMap = {};
            hourlyValues.forEach(h => hourlyMap[h.hour] = h.visits);

            charts.hourly = new Chart(document.getElementById('hourlyChart'), {
                type: 'bar',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Уникальные посещения',
                        data: hours.map((_, i) => hourlyMap[i] || 0),
                        backgroundColor: 'rgba(102, 126, 234, 0.7)',
                        borderColor: 'rgba(102, 126, 234, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: { display: true, text: 'Уникальные посещения' }
                        },
                        y: {
                            title: { display: true, text: 'Час' }
                        }
                    }
                }
            });

            // Круговая диаграмма по городам
            charts.city = new Chart(document.getElementById('cityChart'), {
                type: 'doughnut',
                data: {
                    labels: data.city_data.map(c => c.city),
                    datasets: [{
                        data: data.city_data.map(c => c.unique_visits),
                        backgroundColor: [
                            '#667eea', '#764ba2', '#f093fb', '#4facfe',
                            '#43e97b', '#fa709a', '#fee140', '#30cfd0',
                            '#a18cd1', '#fbc2eb'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, padding: 10 }
                        }
                    }
                }
            });

            // Посещения по дням (линейный график)
            charts.daily = new Chart(document.getElementById('dailyChart'), {
                type: 'line',
                data: {
                    labels: data.daily_data.map(d => d.date),
                    datasets: [{
                        label: 'Уникальные посещения',
                        data: data.daily_data.map(d => d.unique_visits),
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // По устройствам
            charts.device = new Chart(document.getElementById('deviceChart'), {
                type: 'pie',
                data: {
                    labels: data.device_data.map(d => d.device),
                    datasets: [{
                        data: data.device_data.map(d => d.count),
                        backgroundColor: ['#667eea', '#f093fb', '#43e97b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // По браузерам
            charts.browser = new Chart(document.getElementById('browserChart'), {
                type: 'pie',
                data: {
                    labels: data.browser_data.map(b => b.browser),
                    datasets: [{
                        data: data.browser_data.map(b => b.count),
                        backgroundColor: ['#4facfe', '#43e97b', '#fee140', '#fa709a', '#a18cd1']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // По ОС
            charts.os = new Chart(document.getElementById('osChart'), {
                type: 'pie',
                data: {
                    labels: data.os_data.map(o => o.os),
                    datasets: [{
                        data: data.os_data.map(o => o.count),
                        backgroundColor: ['#30cfd0', '#667eea', '#764ba2', '#f093fb', '#43e97b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            document.getElementById('loading').style.display = 'none';
            document.getElementById('dashboard').style.display = 'block';
        }

        // Загрузка при старте
        document.addEventListener('DOMContentLoaded', loadData);
        document.getElementById('days').addEventListener('change', loadData);
    </script>
</body>
</html>
