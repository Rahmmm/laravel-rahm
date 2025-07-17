<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create Booking</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #121212;
      margin: 0;
      padding: 0;
      color: #f1f1f1;
    }

    .top-bar {
      background-color: #1e1e1e;
      color: #ffffff;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
      border-radius: 0 0 16px 16px;
    }

    .top-bar .title {
      font-size: 20px;
      font-weight: 600;
    }

    .top-bar .actions a {
      background-color: #2c2c2c;
      color: #ffffff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: background-color 0.3s, transform 0.2s;
    }

    .top-bar .actions a:hover {
      background-color: #3a3a3a;
      transform: scale(1.05);
    }

    .container {
      max-width: 700px;
      margin: 40px auto;
      background: #1e1e1e;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }

    h2 {
      text-align: center;
      color: #ffffff;
      font-size: 28px;
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin-top: 20px;
      font-weight: 600;
      color: #dddddd;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 14px;
      margin-top: 8px;
      border: 1px solid #444;
      border-radius: 12px;
      font-size: 15px;
      background-color: #2c2c2c;
      color: #f1f1f1;
    }

    input[type="text"]:focus,
    textarea:focus {
      outline: none;
      border-color: #666;
      box-shadow: 0 0 6px #666;
      background-color: #333;
    }

    textarea {
      resize: vertical;
      min-height: 90px;
    }

    button {
      margin-top: 30px;
      width: 100%;
      background-color: #333;
      color: #ffffff;
      border: none;
      padding: 14px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
      background-color: #444;
      transform: translateY(-2px);
    }

    .error {
      color: #ff8a8a;
      background: #2d1f1f;
      border: 1px solid #b25c5c;
      padding: 12px;
      border-radius: 12px;
      margin-top: 15px;
      font-weight: 600;
    }

    #calendar-container {
      margin-top: 15px;
      display: flex;
      justify-content: center;
    }

    #selected-date {
      margin-top: 15px;
      font-weight: 700;
      color: #cccccc;
      text-align: center;
    }

    .flatpickr-calendar {
      font-size: 14px !important;
      width: 100% !important;
      max-width: 500px;
      border-radius: 20px;
      background-color: #1e1e1e !important;
      color: #ffffff !important;
      border: 1px solid #444 !important;
    }

    .flatpickr-months {
      background-color: #333 !important;
      color: #ffffff !important;
    }

    .flatpickr-current-month,
    .flatpickr-weekdays {
      color: #ffffff !important;
      background-color: #222 !important;
    }

    .flatpickr-day {
      color: #dddddd !important;
      border-radius: 10px;
    }

    .flatpickr-day:hover {
      background-color: #555 !important;
      color: white !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
      background-color: #999 !important;
      color: #000 !important;
    }

    .flatpickr-day.booked {
      background-color: #4a2a2a !important;
      color: #ffaaaa !important;
      border-color: #b25c5c !important;
      cursor: not-allowed;
    }
  </style>
</head>
<body>

  <div class="top-bar">
    <div class="title">📅 Create Booking</div>
    <div class="actions">
      <a href="{{ route('dashboard') }}">🔙 Back to Dashboard</a>
    </div>
  </div>

  <div class="container">
    <h2>➕ Create New Booking</h2>

    @if ($errors->any())
      <div class="error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('bookings.store') }}" method="POST">
      @csrf

      <label for="title">Title</label>
      <input type="text" name="title" id="title" value="{{ old('title') }}" required />

      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea>

      <label for="booking_date">Booking Date</label>
      <input
        type="text"
        id="booking_date"
        name="booking_date"
        required
        style="position: absolute; left: -9999px;"
      />
      <div id="calendar-container"></div>
      <p id="selected-date"></p>

      <button type="submit">✅ Book Now</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    const bookedDates = @json($bookedDates ?? []);

    function formatDateToPH(dateObj) {
      const phOffset = 8 * 60;
      const localTime = dateObj.getTime();
      const localOffset = dateObj.getTimezoneOffset();
      const phTime = new Date(localTime + (phOffset + localOffset) * 60000);

      const year = phTime.getFullYear();
      const month = String(phTime.getMonth() + 1).padStart(2, '0');
      const day = String(phTime.getDate()).padStart(2, '0');

      return `${year}-${month}-${day}`;
    }

    flatpickr("#booking_date", {
      inline: true,
      appendTo: document.getElementById('calendar-container'),
      dateFormat: "Y-m-d",
      minDate: "today",
      disable: bookedDates,
      onChange: function (selectedDates, dateStr) {
        document.getElementById('selected-date').innerText = "📅 Selected Date: " + dateStr;
      },
      onDayCreate: function (dObj, dStr, fp, dayElem) {
        const formatted = formatDateToPH(dayElem.dateObj);
        if (bookedDates.includes(formatted)) {
          dayElem.classList.add('booked');
        }
      },
    });
  </script>

</body>
</html>
