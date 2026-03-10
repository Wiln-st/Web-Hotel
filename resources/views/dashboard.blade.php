<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management Dashboard</title>
  <meta name="description" content="Hotel management dashboard for room booking, reservations, and monitoring." />
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#6C3FC5',
            'primary-dark': '#5A2DAF',
            'primary-light': '#EDE7F9',
            sidebar: '#1E1E2D',
            'sidebar-hover': '#2A2A3D',
            dark: '#1A1A2E',
            card: '#FFFFFF',
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #F3F4F8; }
    /* Sidebar active indicator */
    .nav-item.active { background: rgba(108, 63, 197, 0.15); border-left: 3px solid #6C3FC5; }
    .nav-item.active .nav-icon, .nav-item.active .nav-label { color: #6C3FC5; }
    .nav-item { transition: all 0.2s ease; }
    .nav-item:hover { background: rgba(108, 63, 197, 0.08); }
    /* Page transitions */
    .page { display: none; animation: fadeIn 0.3s ease; }
    .page.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #c4b5e0; border-radius: 3px; }
    /* Chart bars animation */
    .chart-bar { transition: height 0.6s cubic-bezier(.4,0,.2,1); }
    /* Card hover effect */
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(108,63,197,0.13); }
    /* Notification delete btn */
    .notif-card .delete-btn { opacity: 0.5; transition: opacity 0.2s, color 0.2s; }
    .notif-card:hover .delete-btn { opacity: 1; color: #ef4444; }
    /* Room card status strip */
    .room-card { position: relative; overflow: hidden; }
    .room-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 4px; }
    .room-card.available::after  { background: #22c55e; }
    .room-card.occupied::after   { background: #ef4444; }
    .room-card.cleaning::after   { background: #eab308; }
    .room-card.maintenance::after{ background: #9ca3af; }
  </style>
</head>
<body class="flex min-h-screen">

  <!-- ============================================================== -->
  <!-- SIDEBAR -->
  <!-- ============================================================== -->
  <aside id="sidebar" class="fixed z-30 top-0 left-0 h-screen w-20 lg:w-64 bg-sidebar text-white flex flex-col transition-all duration-300">
    <!-- Logo -->
    <div class="flex items-center gap-3 px-5 py-6 border-b border-white/10">
      <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-lg font-bold shrink-0">
        <i class="fa-solid fa-hotel"></i>
      </div>
      <span class="text-lg font-bold hidden lg:block">HotelKu</span>
    </div>

    <!-- Nav Items -->
    <nav class="flex-1 flex flex-col gap-1 mt-4 px-2">
      <button onclick="showPage('dashboard')" id="nav-dashboard" class="nav-item active flex items-center gap-4 px-4 py-3 rounded-lg w-full text-left">
        <i class="fa-solid fa-chart-pie nav-icon text-lg w-6 text-center"></i>
        <span class="nav-label text-sm font-medium hidden lg:block">Dashboard</span>
      </button>
      <button onclick="showPage('room')" id="nav-room" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg w-full text-left">
        <i class="fa-solid fa-bed nav-icon text-lg w-6 text-center"></i>
        <span class="nav-label text-sm font-medium hidden lg:block">Room</span>
      </button>
      <button onclick="showPage('reservation')" id="nav-reservation" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg w-full text-left">
        <i class="fa-solid fa-calendar-check nav-icon text-lg w-6 text-center"></i>
        <span class="nav-label text-sm font-medium hidden lg:block">Reservation</span>
      </button>
      <button onclick="showPage('notifications') " id="nav-notifications" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg w-full text-left">
        <i class="fa-solid fa-bell nav-icon text-lg w-6 text-center"></i>
        <span class="nav-label text-sm font-medium hidden lg:block">Notifications</span>
      </button>
    </nav>

    <!-- Log Out -->
    <div class="px-2 pb-6">
      <button onclick="alert('Logged out!'); window.location.href='login.html';" class="nav-item flex items-center gap-4 px-4 py-3 rounded-lg w-full text-left text-red-400 hover:bg-red-500/10">
        <i class="fa-solid fa-right-from-bracket text-lg w-6 text-center"></i>
        <span class="text-sm font-medium hidden lg:block">Log Out</span>
      </button>
    </div>
  </aside>

  <!-- ============================================================== -->
  <!-- MAIN CONTENT -->
  <!-- ============================================================== -->
  <main class="flex-1 ml-20 lg:ml-64 transition-all duration-300">

    <!-- ============================================================== -->
    <!-- TOP BAR -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200 px-6 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary uppercase tracking-wider" id="dayName">TUESDAY</span>
        <span class="text-xs text-gray-400">|</span>
        <span class="text-xs text-gray-500 font-medium" id="currentDate">10 MARET 2026</span>
      </div>
      <div class="flex items-center gap-5">
        <!-- Notifications bell -->
        <button onclick="showPage('notifications')" class="relative p-2 rounded-full hover:bg-primary-light transition">
          <i class="fa-solid fa-bell text-gray-500 text-lg"></i>
          <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        </button>
        <!-- User -->
        <div class="flex items-center gap-2 cursor-pointer">
          <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">U</div>
          <span class="text-sm font-semibold text-gray-700 hidden sm:block">User</span>
        </div>
      </div>
    </header>

    <!-- Pages Container -->
    <div class="p-6">

      <!-- ============================================================== -->
      <!-- DASHBOARD PAGE -->
      <!-- ============================================================== -->
      <section id="page-dashboard" class="page active">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
          <!-- Left Column: Stats -->
          <div class="xl:col-span-1 flex flex-col gap-4">
            <!-- Total Rooms -->
            <div class="hover-lift bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
              <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                <i class="fa-solid fa-building text-primary text-xl"></i>
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium">Total Rooms</p>
                <p class="text-2xl font-bold text-gray-800">120</p>
              </div>
            </div>
            <!-- Available -->
            <div class="hover-lift bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
              <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fa-solid fa-door-open text-green-500 text-xl"></i>
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium">Available</p>
                <p class="text-2xl font-bold text-gray-800">86</p>
              </div>
            </div>
            <!-- Occupied -->
            <div class="hover-lift bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
              <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                <i class="fa-solid fa-user-check text-red-500 text-xl"></i>
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium">Occupied</p>
                <p class="text-2xl font-bold text-gray-800">30</p>
              </div>
            </div>
            <!-- Cleaning -->
            <div class="hover-lift bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
              <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
                <i class="fa-solid fa-broom text-yellow-500 text-xl"></i>
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium">Cleaning</p>
                <p class="text-2xl font-bold text-gray-800">12</p>
              </div>
            </div>
            <!-- Maintenance -->
            <div class="hover-lift bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4 border border-gray-100">
              <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                <i class="fa-solid fa-wrench text-gray-500 text-xl"></i>
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium">Maintenance</p>
                <p class="text-2xl font-bold text-gray-800">2</p>
              </div>
            </div>
          </div>

          <!-- Right Column: Payment + Transactions + Chart -->
          <div class="xl:col-span-2 flex flex-col gap-6">
            <!-- Payment Card -->
            <div class="hover-lift bg-gradient-to-br from-primary via-primary-dark to-[#4A1F9E] rounded-2xl shadow-lg p-8 text-white">
              <h2 class="text-3xl font-extrabold mb-2 italic" style="font-family:'Georgia',serif;">Payment</h2>
              <p class="text-white/70 text-sm">Manage and track all payment activities</p>
              <div class="mt-6 flex items-center gap-3">
                <span class="bg-white/20 px-4 py-2 rounded-lg text-sm font-semibold">Total Revenue</span>
                <span class="text-2xl font-bold">$124,500</span>
              </div>
            </div>

            <!-- Recent Transactions + Chart -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Recent Transactions -->
              <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-bold text-gray-800">Recent Transactions</h3>
                  <span class="text-[10px] text-gray-400 font-medium">TODAY | 03 MAR, 2026</span>
                </div>
                <div class="flex flex-col gap-3">
                  <!-- Transaction 1 -->
                  <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-primary-light transition">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                      <i class="fa-solid fa-arrow-down text-green-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                      <p class="text-sm font-semibold text-gray-700">Room reservation</p>
                      <p class="text-[10px] text-gray-400">08:30 AM</p>
                    </div>
                    <span class="text-sm font-bold text-green-600">+$432.00</span>
                  </div>
                  <!-- Transaction 2 -->
                  <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-primary-light transition">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
                      <i class="fa-solid fa-arrow-up text-red-500 text-sm"></i>
                    </div>
                    <div class="flex-1">
                      <p class="text-sm font-semibold text-gray-700">Cleaning costs</p>
                      <p class="text-[10px] text-gray-400">10:15 AM</p>
                    </div>
                    <span class="text-sm font-bold text-red-500">-$1,500</span>
                  </div>
                  <!-- Transaction 3 -->
                  <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-primary-light transition">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
                      <i class="fa-solid fa-arrow-up text-red-500 text-sm"></i>
                    </div>
                    <div class="flex-1">
                      <p class="text-sm font-semibold text-gray-700">Property costs</p>
                      <p class="text-[10px] text-gray-400">02:45 PM</p>
                    </div>
                    <span class="text-sm font-bold text-red-500">-$4,174</span>
                  </div>
                </div>
              </div>

              <!-- Statistik Chart -->
              <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Statistik</h3>
                <div class="flex items-end justify-around h-40 gap-2">
                  <div class="flex flex-col items-center gap-1">
                    <div class="chart-bar w-8 rounded-t-md bg-primary/30" style="height:40%"></div>
                    <span class="text-[10px] text-gray-400">Mon</span>
                  </div>
                  <div class="flex flex-col items-center gap-1">
                    <div class="chart-bar w-8 rounded-t-md bg-primary/50" style="height:65%"></div>
                    <span class="text-[10px] text-gray-400">Tue</span>
                  </div>
                  <div class="flex flex-col items-center gap-1">
                    <div class="chart-bar w-8 rounded-t-md bg-primary/40" style="height:50%"></div>
                    <span class="text-[10px] text-gray-400">Wed</span>
                  </div>
                  <div class="flex flex-col items-center gap-1">
                    <div class="chart-bar w-8 rounded-t-md bg-primary/70" style="height:80%"></div>
                    <span class="text-[10px] text-gray-400">Thu</span>
                  </div>
                  <div class="flex flex-col items-center gap-1">
                    <div class="chart-bar w-8 rounded-t-md bg-primary" style="height:95%"></div>
                    <span class="text-[10px] text-gray-400">Fri</span>
                  </div>
                  <div class="flex flex-col items-center gap-1">
                    <div class="chart-bar w-8 rounded-t-md bg-primary/60" style="height:70%"></div>
                    <span class="text-[10px] text-gray-400">Sat</span>
                  </div>
                  <div class="flex flex-col items-center gap-1">
                    <div class="chart-bar w-8 rounded-t-md bg-primary/35" style="height:45%"></div>
                    <span class="text-[10px] text-gray-400">Sun</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============================================================== -->
      <!-- ROOM PAGE -->
      <!-- ============================================================== -->
      <section id="page-room" class="page">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Room Management</h1>

        <!-- Room Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
          <div class="hover-lift bg-white rounded-2xl shadow-sm p-4 text-center border border-gray-100">
            <div class="w-10 h-10 mx-auto rounded-xl bg-primary/10 flex items-center justify-center mb-2">
              <i class="fa-solid fa-building text-primary"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">120</p>
            <p class="text-[11px] text-gray-400 font-medium">Total Rooms</p>
          </div>
          <div class="hover-lift bg-white rounded-2xl shadow-sm p-4 text-center border border-gray-100">
            <div class="w-10 h-10 mx-auto rounded-xl bg-green-50 flex items-center justify-center mb-2">
              <i class="fa-solid fa-door-open text-green-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">86</p>
            <p class="text-[11px] text-gray-400 font-medium">Available</p>
          </div>
          <div class="hover-lift bg-white rounded-2xl shadow-sm p-4 text-center border border-gray-100">
            <div class="w-10 h-10 mx-auto rounded-xl bg-red-50 flex items-center justify-center mb-2">
              <i class="fa-solid fa-user-check text-red-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">30</p>
            <p class="text-[11px] text-gray-400 font-medium">Occupied</p>
          </div>
          <div class="hover-lift bg-white rounded-2xl shadow-sm p-4 text-center border border-gray-100">
            <div class="w-10 h-10 mx-auto rounded-xl bg-yellow-50 flex items-center justify-center mb-2">
              <i class="fa-solid fa-broom text-yellow-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">12</p>
            <p class="text-[11px] text-gray-400 font-medium">Cleaning</p>
          </div>
          <div class="hover-lift bg-white rounded-2xl shadow-sm p-4 text-center border border-gray-100">
            <div class="w-10 h-10 mx-auto rounded-xl bg-gray-100 flex items-center justify-center mb-2">
              <i class="fa-solid fa-wrench text-gray-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">2</p>
            <p class="text-[11px] text-gray-400 font-medium">Maintenance</p>
          </div>
        </div>

        <!-- Floor 1 -->
        <div class="mb-8">
          <h2 class="text-lg font-bold text-gray-700 mb-4">Floor 1</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <!-- Room cards Floor 1 -->
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 001</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card occupied hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 002</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-600">Occupied</span>
            </div>
            <div class="room-card cleaning hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 003</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Cleaning</span>
            </div>
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 004</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 005</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card occupied hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 006</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-600">Occupied</span>
            </div>
            <div class="room-card occupied hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 007</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-600">Occupied</span>
            </div>
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 008</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 009</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card maintenance hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 010</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-200 text-gray-600">Maintenance</span>
            </div>
          </div>
        </div>

        <!-- Floor 2 -->
        <div class="mb-8">
          <h2 class="text-lg font-bold text-gray-700 mb-4">Floor 2</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 001</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 002</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card occupied hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 003</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-600">Occupied</span>
            </div>
            <div class="room-card available hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 004</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Available</span>
            </div>
            <div class="room-card maintenance hover-lift bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
              <p class="text-sm font-bold text-gray-800">Room 005</p>
              <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-200 text-gray-600">Maintenance</span>
            </div>
          </div>
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap gap-4 mt-4">
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span><span class="text-xs text-gray-500">Available</span></div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span><span class="text-xs text-gray-500">Occupied</span></div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-yellow-500"></span><span class="text-xs text-gray-500">Cleaning</span></div>
          <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-400"></span><span class="text-xs text-gray-500">Maintenance</span></div>
        </div>
      </section>

      <!-- ============================================================== -->
      <!-- RESERVATION PAGE -->
      <!-- ============================================================== -->
      <section id="page-reservation" class="page">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Reservation</h1>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
          <!-- Form Card -->
          <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5 italic" style="font-family:'Georgia',serif;">Reservation</h2>
            <form id="reservationForm" class="flex flex-col gap-4">
              <!-- Guest Name -->
              <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Guest Name</label>
                <input id="guestName" type="text" placeholder="Enter guest name" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition" />
              </div>
              <!-- Phone Number -->
              <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Phone Number</label>
                <input id="phoneNumber" type="tel" placeholder="Enter phone number" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition" />
              </div>
              <!-- Room Number & Room Category -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1">Room Number</label>
                  <select id="roomNumber" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 bg-white transition">
                    <option value="">Select Room</option>
                    <option value="001">Room 001</option>
                    <option value="002">Room 002</option>
                    <option value="003">Room 003</option>
                    <option value="004">Room 004</option>
                    <option value="005">Room 005</option>
                    <option value="008">Room 008</option>
                    <option value="009">Room 009</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1">Room Category</label>
                  <select id="roomCategory" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 bg-white transition">
                    <option value="standard">Standard - $65/night</option>
                    <option value="deluxe">Deluxe - $120/night</option>
                    <option value="suite">Suite - $250/night</option>
                  </select>
                </div>
              </div>
              <!-- Dates -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1">Check In</label>
                  <input id="checkIn" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition" />
                </div>
                <div>
                  <label class="block text-xs font-semibold text-gray-500 mb-1">Check Out</label>
                  <input id="checkOut" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 transition" />
                </div>
              </div>
              <!-- Calculate Button -->
              <button type="button" onclick="calculatePayment()" class="mt-2 bg-primary hover:bg-primary-dark text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 active:scale-[0.98]">
                <i class="fa-solid fa-calculator mr-2"></i>Calculate
              </button>
            </form>
          </div>

          <!-- Total Payment Card -->
          <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col">
            <h2 class="text-lg font-bold text-gray-800 mb-5 italic" style="font-family:'Georgia',serif;">Total</h2>
            <div class="flex flex-col gap-3 flex-1">
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Price per Night</span>
                <span class="font-semibold text-gray-800" id="pricePerNight">$0.0</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Number of Nights</span>
                <span class="font-semibold text-gray-800" id="numNights">0</span>
              </div>
              <hr class="my-2 border-gray-100" />
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span class="font-semibold text-gray-800" id="subtotal">$0.00</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Tax (10%)</span>
                <span class="font-semibold text-gray-800" id="tax">$0.00</span>
              </div>
              <hr class="my-2 border-gray-100" />
              <div class="flex justify-between text-base mt-auto">
                <span class="font-bold text-gray-800">Total Payment</span>
                <span class="font-extrabold text-primary text-xl" id="totalPayment">$0.00</span>
              </div>
            </div>
            <!-- Save Button -->
            <button onclick="saveReservation()" class="mt-5 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 active:scale-[0.98]">
              <i class="fa-solid fa-floppy-disk mr-2"></i>Save
            </button>
          </div>
        </div>
      </section>

      <!-- ============================================================== -->
      <!-- NOTIFICATIONS PAGE -->
      <!-- ============================================================== -->
      <section id="page-notifications" class="page">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 italic" style="font-family:'Georgia',serif;">Notifications</h1>

        <div class="flex flex-col gap-4 max-w-2xl" id="notifContainer">
          <!-- Notification 1 -->
          <div class="notif-card hover-lift bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-circle-info text-primary"></i>
            </div>
            <div class="flex-1">
              <h3 class="text-sm font-bold text-gray-800">Room 003 - Cleaning Complete</h3>
              <p class="text-xs text-gray-400 mt-1">Room 003 has been cleaned and is now available for the next guest. Updated status to Available.</p>
            </div>
            <button onclick="deleteNotif(this)" class="delete-btn p-2 rounded-lg hover:bg-red-50 transition">
              <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
          </div>
          <!-- Notification 2 -->
          <div class="notif-card hover-lift bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
            </div>
            <div class="flex-1">
              <h3 class="text-sm font-bold text-gray-800">Maintenance Required - Room 010</h3>
              <p class="text-xs text-gray-400 mt-1">AC unit in Room 010 requires servicing. Maintenance team has been notified. Estimated fix in 2 hours.</p>
            </div>
            <button onclick="deleteNotif(this)" class="delete-btn p-2 rounded-lg hover:bg-red-50 transition">
              <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
          </div>
          <!-- Notification 3 -->
          <div class="notif-card hover-lift bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-check-circle text-green-500"></i>
            </div>
            <div class="flex-1">
              <h3 class="text-sm font-bold text-gray-800">New Reservation Confirmed</h3>
              <p class="text-xs text-gray-400 mt-1">Guest John Doe has confirmed booking for Room 005 from Mar 12 - Mar 15, 2026. Total: $750.</p>
            </div>
            <button onclick="deleteNotif(this)" class="delete-btn p-2 rounded-lg hover:bg-red-50 transition">
              <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
          </div>
          <!-- Notification 4 -->
          <div class="notif-card hover-lift bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0 mt-0.5">
              <i class="fa-solid fa-bell text-red-500"></i>
            </div>
            <div class="flex-1">
              <h3 class="text-sm font-bold text-gray-800">Check-out Reminder</h3>
              <p class="text-xs text-gray-400 mt-1">Guest in Room 006 is scheduled for check-out today at 12:00 PM. Please prepare the room for inspection.</p>
            </div>
            <button onclick="deleteNotif(this)" class="delete-btn p-2 rounded-lg hover:bg-red-50 transition">
              <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
          </div>
        </div>
      </section>

    </div><!-- end p-6 container -->
  </main>

  <!-- ============================================================== -->
  <!-- JAVASCRIPT -->
  <!-- ============================================================== -->
  <script>
    // ---- Set current date in top bar ----
    (function setDate() {
      const days = ['SUNDAY','MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY'];
      const months = ['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];
      const now = new Date();
      document.getElementById('dayName').textContent = days[now.getDay()];
      document.getElementById('currentDate').textContent =
        now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
    })();

    // ---- Page Navigation ----
    function showPage(page) {
      // Hide all pages
      document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
      // Show target page
      document.getElementById('page-' + page).classList.add('active');
      // Update sidebar active state
      document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
      const navBtn = document.getElementById('nav-' + page);
      if (navBtn) navBtn.classList.add('active');
    }

    // ---- Calculate Payment ----
    function calculatePayment() {
      const category = document.getElementById('roomCategory').value;
      const checkIn = new Date(document.getElementById('checkIn').value);
      const checkOut = new Date(document.getElementById('checkOut').value);

      if (!document.getElementById('checkIn').value || !document.getElementById('checkOut').value) {
        alert('Please select check-in and check-out dates.');
        return;
      }
      if (checkOut <= checkIn) {
        alert('Check-out date must be after check-in date.');
        return;
      }

      const prices = { standard: 65, deluxe: 120, suite: 250 };
      const pricePerNight = prices[category] || 65;
      const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
      const subtotal = pricePerNight * nights;
      const taxAmount = subtotal * 0.10;
      const total = subtotal + taxAmount;

      document.getElementById('pricePerNight').textContent = '$' + pricePerNight.toFixed(1);
      document.getElementById('numNights').textContent = nights;
      document.getElementById('subtotal').textContent = '$' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2});
      document.getElementById('tax').textContent = '$' + taxAmount.toLocaleString('en-US', {minimumFractionDigits: 2});
      document.getElementById('totalPayment').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2});
    }

    // ---- Save Reservation ----
    function saveReservation() {
      const name = document.getElementById('guestName').value;
      const room = document.getElementById('roomNumber').value;
      const total = document.getElementById('totalPayment').textContent;
      if (!name || !room) {
        alert('Please fill in guest name and select a room.');
        return;
      }
      if (total === '$0.00') {
        alert('Please calculate payment first.');
        return;
      }
      alert('Reservation saved!\n\nGuest: ' + name + '\nRoom: ' + room + '\nTotal: ' + total);
      // Reset form
      document.getElementById('reservationForm').reset();
      document.getElementById('pricePerNight').textContent = '$0.0';
      document.getElementById('numNights').textContent = '0';
      document.getElementById('subtotal').textContent = '$0.00';
      document.getElementById('tax').textContent = '$0.00';
      document.getElementById('totalPayment').textContent = '$0.00';
    }

    // ---- Delete Notification ----
    function deleteNotif(btn) {
      const card = btn.closest('.notif-card');
      card.style.transition = 'all 0.3s ease';
      card.style.opacity = '0';
      card.style.transform = 'translateX(40px)';
      setTimeout(() => card.remove(), 300);
    }
  </script>
</body>
</html>
