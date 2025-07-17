<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Booking</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #121212;
      margin: 0;
      padding: 0;
      color: #f0f0f0;
    }

    .top-bar {
      background-color: #1e1e1e;
      color: #ffffff;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
      border-bottom: 1px solid #2a2a2a;
    }

    .top-bar .title {
      font-size: 20px;
      font-weight: 600;
    }

    .top-bar .actions a {
      background-color: #333333;
      color: #f0f0f0;
      padding: 10px 18px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: background-color 0.3s;
    }

    .top-bar .actions a:hover {
      background-color: #444444;
    }

    .container {
      max-width: 700px;
      margin: 40px auto;
      background: #1c1c1c;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }

    h2 {
      text-align: center;
      font-size: 26px;
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin-top: 20px;
      font-weight: 600;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 14px;
      margin-top: 8px;
      border: 1px solid #333;
      border-radius: 8px;
      background-color: #2a2a2a;
      color: #f0f0f0;
      font-size: 15px;
    }

    input[type="text"]:focus,
    textarea:focus {
      outline: none;
      border-color: #555;
      background-color: #1e1e1e;
    }

    textarea {
      resize: vertical;
      min-height: 90px;
    }

    button {
      margin-top: 30px;
      width: 100%;
      background-color: #333;
      color: #fff;
      border: none;
      padding: 14px;
      border-radius: 8px;
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
      color: #ff6b6b;
      background: #2b1f1f;
      border: 1px solid #ff6b6b;
      padding: 12px;
      border-radius: 8px;
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
      text-align: center;
    }

    /* === CALENDAR CUSTOM STYLING === */
    .flatpickr-calendar {
      font-size: 14px !important;
      max-width: 500px;
      border-radius: 12px;
      background-color: #1e1e1e !important;
      color: #ffffff !important;
      border: 1px solid #333 !important;
    }

    .flatpickr-months {
      background-color: #222 !important;
      color: #fff !important;
    }

    .flatpickr-current-month {
      color: #fff !important;
    }

    .flatpickr-weekdays {
      background-color: #292929 !important;
    }

    .flatpickr-weekday {
      color: #ccc !important;
    }

    .flatpickr-day {
      color: #eee !important;
      border-radius: 6px;
    }

    .flatpickr-day:hover {
      background-color: #444 !important;
    }

    .flatpickr-day.selected {
      background-color: #666 !important;
      color: #fff !important;
    }

    .flatpickr-day.booked {
      background-color: #5a2e2e !important;
      color: #ff6b6b !important;
      cursor: not-allowed;
    }

    .flatpickr-monthDropdown-months,
    .flatpickr-current-month input.cur-year {
      background-color: #333 !important;
      color: #fff !important;
      border: none !important;
    }
  </style>
</head>
<body>

<div class="top-bar">
  <div class="title">✏️ Edit Booking</div>
  <div class="actions">
    <a href="{{ route('bookings.index') }}">🔙 Back to Bookings</a>
  </div>
</div>

<div class="container">
  <h2>Update Your Booking</h2>

  @if ($errors->any())
    <div class="error">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="title">Title</label>
    <input type="text" name="title" id="title" value="{{ old('title', $booking->title) }}" required />

    <label for="description">Description</label>
    <textarea name="description" id="description">{{ old('description', $booking->description) }}</textarea>

    <label for="booking_date">Booking Date</label>
    <input
      type="text"
      id="booking_date"
      name="booking_date"
      required
      style="position: absolute; left: -9999px;"
      value="{{ old('booking_date', $booking->booking_date) }}"
      readonly
    />
    <div id="calendar-container"></div>
    <p id="selected-date">📅 Selected Date: {{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</p>

    <button type="submit">✅ Update Booking</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  const bookedDates = @json($bookedDates ?? []);
  const currentDate = "{{ old('booking_date', $booking->booking_date) }}";

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
    defaultDate: currentDate,
    disable: bookedDates.filter(date => date !== currentDate),
    onChange: function (selectedDates, dateStr) {
      document.getElementById('selected-date').innerText = "📅 Selected Date: " + dateStr;
    },
    onDayCreate: function (dObj, dStr, fp, dayElem) {
      const formatted = formatDateToPH(dayElem.dateObj);
      if (bookedDates.includes(formatted) && formatted !== currentDate) {
        dayElem.classList.add('booked');
      }
    },
  });
</script>

</body>
</html>
