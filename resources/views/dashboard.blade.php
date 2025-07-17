<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking Dashboard - Dark Theme</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #1e1e1e;
      color: #e0e0e0;
      margin: 0;
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 240px;
      background: #2c2c2c;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      color: #f0f0f0;
      border-right: 1px solid #444;
    }

    .title-container {
      overflow: hidden;
      white-space: nowrap;
      width: 100%;
      margin-bottom: 20px;
    }

    .title {
      display: inline-block;
      font-size: 36px;
      font-weight: 900;
      padding-left: 100%;
      background: linear-gradient(270deg, #ffffff, #cccccc);
      background-size: 800% 800%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: gradientMove 12s ease infinite, marqueeMove 10s linear infinite;
    }

    @keyframes gradientMove {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    @keyframes marqueeMove {
      0% { transform: translateX(0%); }
      100% { transform: translateX(-100%); }
    }

    .sidebar a {
      display: block;
      color: #ccc;
      text-decoration: none;
      padding: 12px 16px;
      margin: 8px 0;
      border-radius: 8px;
      background: #3b3b3b;
      transition: background 0.3s, color 0.3s;
    }

    .sidebar a:hover {
      background: #505050;
      color: #fff;
    }

    .logout-form button {
      width: 100%;
      background: #444;
      border: 1px solid #666;
      padding: 10px;
      border-radius: 8px;
      color: #eee;
      font-weight: 600;
      cursor: pointer;
    }

    .logout-form button:hover {
      background: #555;
    }

    .content {
      flex: 1;
      padding: 40px;
      color: #ddd;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      background-color: #2a2a2a;
      padding: 20px 40px;
      align-items: center;
      border-radius: 12px;
    }

    .top-bar a,
    .top-bar button {
      background-color: #444;
      color: #ddd;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      cursor: pointer;
    }

    .top-bar a:hover,
    .top-bar button:hover {
      background-color: #666;
    }

    .container {
      margin-top: 20px;
      background: #2c2c2c;
      padding: 40px;
      border-radius: 20px;
      border: 1px solid #444;
      color: #ccc;
    }

    h1 {
      margin-bottom: 20px;
      font-size: 28px;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .box {
      background: #3a3a3a;
      padding: 20px;
      border-radius: 16px;
      font-size: 18px;
      color: #f0f0f0;
      font-weight: 600;
      text-align: center;
      transition: background 0.3s;
    }

    .box:hover {
      background: #505050;
    }

    #calendar {
      margin: 0 auto;
      max-width: 900px;
      background: #1f1f1f;
      border: 1px solid #444;
      border-radius: 12px;
      color: #fff;
    }

    .fc .fc-daygrid-day-frame {
      background-color: #2c2c2c;
    }

    .fc-daygrid-day.booked {
      background-color: #993333 !important;
      color: #fff !important;
      pointer-events: none;
    }

    .fab {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background-color: #444;
      color: #fff;
      width: 56px;
      height: 56px;
      border-radius: 50%;
      font-size: 28px;
      text-align: center;
      line-height: 56px;
      cursor: pointer;
      text-decoration: none;
      font-weight: 700;
    }

    .fab:hover {
      background-color: #666;
    }
  </style>
</head>
<body>

  <aside class="sidebar">
    <div class="title-container">
      <div class="title">BOO-King</div>
    </div>
    <nav>
      <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
      <a href="{{ route('bookings.index') }}">📖 View Bookings</a>
      <a href="{{ route('bookings.create') }}">➕ Create Booking</a>
    </nav>
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
      @csrf
      <button type="submit">🚪 Logout</button>
    </form>
  </aside>

  <div class="content">
    <div class="top-bar">
      <h1>Welcome, {{ auth()->user()->name }}</h1>
      <div>
        <a href="{{ route('profile.edit') }}">👤 Edit Profile</a>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
          @csrf
          <button type="submit">🚪 Logout</button>
        </form>
      </div>
    </div>

    <div class="container">
      <div class="stats">
        <div class="box">📅<br><strong>Total Bookings:</strong><br>{{ $totalBookings }}</div>
        <div class="box">👤<br><strong>Total Users:</strong><br>{{ $totalUsers }}</div>
      </div>
      <div id="calendar"></div>
    </div>
  </div>

  <a href="{{ route('bookings.create') }}" class="fab" title="Create New Booking">➕</a>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const booked = @json($bookedDates ?? []);

      function formatDate(date) {
        return date.toLocaleDateString('en-CA');
      }

      const calendarEl = document.getElementById('calendar');

      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: false,
        dateClick: function(info) {
          const clickedDate = formatDate(new Date(info.date));
          if (booked.includes(clickedDate)) return;
          alert('You clicked ' + clickedDate);
        },
        dayCellDidMount: function(arg) {
          const day = formatDate(arg.date);
          if (booked.includes(day)) {
            arg.el.classList.add('booked');
            arg.el.setAttribute('title', 'Already Booked');
          }
        },
      });

      calendar.render();
    });
  </script>
</body>
</html>
