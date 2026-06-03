<x-layout>
    <style>
        /* Style zamknięte w komponencie, z przedrostkami 'walks-' aby nie psuć szablonu Pawła */
        .walks-wrapper {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding: 20px 0;
        }

        .walks-header {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .walks-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .walks-panel {
            background-color: #ffffff;
            flex: 1;
            min-width: 300px;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }

        .walks-panel h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            display: inline-block;
        }

        .walks-form-group {
            margin-bottom: 15px;
        }

        .walks-form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 14px;
        }

        .walks-form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .walks-btn {
            width: 100%;
            background-color: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .walks-btn:hover {
            background-color: #219150;
        }

        .walks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .walks-table th, .walks-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eeeeee;
        }

        .walks-table th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
        }

        .walks-badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        
        .walks-badge.pending { background-color: #f39c12; }
        .walks-badge.done { background-color: #27ae60; }
    </style>

    <div class="walks-wrapper">
        <div class="walks-header">
            🐾 System Zarządzania Spacerami
        </div>

        <div class="walks-container">
            <div class="walks-panel">
                <h2>Zaplanuj nowy spacer</h2>
                <form id="walkForm" onsubmit="fakeSubmit(event)">
                    <div class="walks-form-group">
                        <label>Data spaceru</label>
                        <input type="date" name="date" required value="2026-06-01">
                    </div>
                    <div class="walks-form-group">
                        <label>Godzina</label>
                        <input type="time" name="time" required>
                    </div>
                    <div class="walks-form-group">
                        <label>ID Wolontariusza</label>
                        <input type="number" name="volunteer_id" placeholder="Np. 1" required>
                    </div>
                    <div class="walks-form-group">
                        <label>ID Psa</label>
                        <input type="number" name="dog_id" placeholder="Np. 2" required>
                    </div>
                    <div class="walks-form-group">
                        <label>ID Pracownika Nadzorującego</label>
                        <input type="number" name="supervisor_id" placeholder="Np. 1" required>
                    </div>
                    <button type="submit" class="walks-btn">Zarezerwuj spacer</button>
                </form>
            </div>

            <div class="walks-panel">
                <h2>Dzisiejsze spacery</h2>
                <table class="walks-table">
                    <thead>
                        <tr>
                            <th>Godzina</th>
                            <th>Pies</th>
                            <th>Wolontariusz</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>12:00</td>
                            <td>Reksio</td>
                            <td>Dominik Domański</td>
                            <td><span class="walks-badge pending">Oczekujący</span></td>
                        </tr>
                        <tr>
                            <td>14:30</td>
                            <td>Tofik</td>
                            <td>Jagoda Knyc</td>
                            <td><span class="walks-badge done">Zakończony</span></td>
                        </tr>
                        <tr>
                            <td>16:00</td>
                            <td>Rambo</td>
                            <td>Marian Knyc</td>
                            <td><span class="walks-badge pending">Oczekujący</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function fakeSubmit(e) {
            e.preventDefault();
            alert('Świetnie! Spacer został pomyślnie zarezerwowany. (To jest mockup frontendu na zaliczenie)');
            document.getElementById('walkForm').reset();
        }
    </script>
</x-layout>