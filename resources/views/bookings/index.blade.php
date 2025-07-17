<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>📅 View Bookings</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9f9f9;
      color: #2d2d2d;
      margin: 0;
      padding: 0;
    }

    .top-bar {
      background-color: #1f1f1f;
      color: #ffffff;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #444;
    }

    .top-bar .title {
      font-size: 20px;
      font-weight: 600;
    }

    .top-bar .nav-links a {
      background-color: #333;
      color: #fff;
      padding: 10px 16px;
      border-radius: 8px;
      text-decoration: none;
      margin-left: 12px;
      transition: background 0.3s ease;
      font-weight: 600;
    }

    .top-bar .nav-links a:hover {
      background-color: #555;
    }

    .container {
      max-width: 800px;
      margin: 40px auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 28px;
      font-weight: 700;
    }

    .notification {
      background-color: #e0e0e0;
      color: #2d2d2d;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 600;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 14px 16px;
      text-align: left;
      border-bottom: 1px solid #ccc;
    }

    th {
      background-color: #f0f0f0;
      font-weight: 600;
    }

    tr:hover {
      background-color: #f5f5f5;
    }

    .no-bookings {
      text-align: center;
      padding: 20px;
      font-weight: 600;
      background-color: #f0f0f0;
      border-radius: 8px;
      margin-top: 20px;
    }

    .action-buttons {
      display: flex;
      gap: 10px;
    }

    .edit-btn, .delete-btn {
      display: inline-block;
      padding: 8px 14px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.2s ease;
      border: none;
    }

    .edit-btn {
      background-color: #444;
      color: #fff;
    }

    .edit-btn:hover {
      background-color: #666;
    }

    .delete-btn {
      background-color: #888;
      color: #fff;
    }

    .delete-btn:hover {
      background-color: #aaa;
    }

    form {
      margin: 0;
    }

    @media (max-width: 768px) {
      .action-buttons {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <div class="top-bar">
    <div class="title">📅 View Bookings</div>
    <div class="nav-links">
      <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
      <a href="{{ route('bookings.create') }}">➕ New Booking</a>
    </div>
  </div>

  <div class="container">

    @if (session('success'))
      <div class="notification">
        {{ session('success') }}
      </div>
    @endif

    <h1>Your Bookings</h1>

    @if($bookings->isEmpty())
      <div class="no-bookings">You don't have any bookings yet.</div>
    @else
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Booking Date</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bookings as $booking)
            <tr>
              <td>{{ $booking->title }}</td>
              <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</td>
              <td>{{ $booking->description }}</td>
              <td>
                <div class="action-buttons">
                  <a href="{{ route('bookings.edit', $booking->id) }}" class="edit-btn" title="Edit Booking">✏️ Edit</a>
                  <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn" title="Delete Booking">🗑️ Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif

  </div>

</body>
</html>
