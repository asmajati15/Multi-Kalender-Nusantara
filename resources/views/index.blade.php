<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Multi Calendar Nusantara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FBFBF7] text-[#34402F] antialiased selection:bg-[#D5DDC8] selection:text-[#34402F]">

    <main class="min-h-screen">
        @include('components.landing.floating-navbar')
        @include('components.landing.hero-section')
        @include('components.landing.conversion-section')
        @include('components.landing.calendar-preview')
        @include('components.landing.feature-section')
        @include('components.landing.footer')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Typing Animation
            const typingTexts = [
                'Konversi Masehi ke Hijriyah, Jawa, dan Sunda.',
                'Ubah tanggal Hijriyah, Jawa, atau Sunda kembali ke Masehi.',
                'Lihat nama hari dan bulan dari setiap sistem kalender.',
                'Tampilkan kalender utama sesuai sistem tanggal pilihanmu.',
                'Bandingkan beberapa kalender dalam satu tampilan rapi.'
            ];
            const typingElement = document.getElementById('typing-text');

            let textIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            let typingDelay = 80;
            let erasingDelay = 40;
            let newTextDelay = 2000;

            function type() {
                if (!typingElement) return;
                const currentText = typingTexts[textIndex];

                if (isDeleting) {
                    typingElement.textContent = currentText.substring(0, charIndex - 1);
                    charIndex--;
                } else {
                    typingElement.textContent = currentText.substring(0, charIndex + 1);
                    charIndex++;
                }

                let typeSpeed = isDeleting ? erasingDelay : typingDelay;

                if (!isDeleting && charIndex === currentText.length) {
                    typeSpeed = newTextDelay;
                    isDeleting = true;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    textIndex = (textIndex + 1) % typingTexts.length;
                    typeSpeed = 500; // Pause before start typing new text
                }

                setTimeout(type, typeSpeed);
            }

            // Start typing
            if (typingElement) {
                setTimeout(type, 1000);
            }

            // Multi Calendar Render Logic
            const calendarGrid = document.getElementById('calendar-grid');
            const mainCalendarSelect = document.getElementById('main-calendar-select');
            const daysHeader = document.getElementById('calendar-days-header');
            const monthSelect = document.getElementById('calendar-month-select');
            const yearSelect = document.getElementById('calendar-year-select');

            const dayNamesHeader = {
                gregorian: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                hijri: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'],
                javanese: ['Senen', 'Selasa', 'Rebo', 'Kemis', 'Jemuah', 'Setu', 'Ahad'],
                sundanese: ['Soma', 'Anggara', 'Buda', 'Respati', 'Sukra', 'Tumpek', 'Radite']
            };

            const checkBoxes = {
                gregorian: document.getElementById('show-gregorian'),
                hijri: document.getElementById('show-hijri'),
                javanese: document.getElementById('show-javanese'),
                sundanese: document.getElementById('show-sundanese')
            };

            const labels = {
                gregorian: 'Masehi',
                hijri: 'Hijriyah',
                javanese: 'Jawa',
                sundanese: 'Sunda'
            };

            const checkContainers = {
                gregorian: document.getElementById('show-gregorian-container'),
                hijri: checkBoxes.hijri.closest('label'),
                javanese: checkBoxes.javanese.closest('label'),
                sundanese: checkBoxes.sundanese.closest('label')
            };

            let multiCalendarDays = [];
            let currentCalendar = 'gregorian';
            let currentYear = new Date().getFullYear();
            let currentMonth = new Date().getMonth() + 1;

            const today = new Date();
            const todayYear = today.getFullYear();
            const todayMonth = today.getMonth() + 1;
            const todayDate = today.getDate();

            let initialLoad = true;

            const prevMonthBtn = document.getElementById('prev-month-btn');
            const nextMonthBtn = document.getElementById('next-month-btn');

            // Modal Elements
            const modal = document.getElementById('mobile-date-modal');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const modalMainDate = document.getElementById('modal-main-date');
            const modalMainDay = document.getElementById('modal-main-day');
            const modalDetailsContainer = document.getElementById('modal-details-container');

            function openModal(dayData, mainCal) {
                if(!dayData || dayData.gregorian.day === '') return;

                const mainSelected = dayData[mainCal];
                modalMainDay.textContent = mainSelected.dayName;
                modalMainDate.textContent = `${mainSelected.day} ${mainSelected.monthName} ${mainSelected.year}`;

                modalDetailsContainer.innerHTML = '';

                // Add all calendars except main
                Object.keys(labels).forEach(key => {
                    if (key !== mainCal) {
                        const d = dayData[key];
                        const div = document.createElement('div');
                        div.className = "bg-white p-3 rounded-xl border border-[#D5DDC8] shadow-sm";
                        div.innerHTML = `
                            <h4 class="text-[10px] font-bold text-[#647754] uppercase tracking-wider mb-1">${labels[key]}</h4>
                            <p class="text-sm font-medium text-[#34402F]">${d.dayName}, ${d.day} ${d.monthName} ${d.year}</p>
                        `;
                        modalDetailsContainer.appendChild(div);
                    }
                });

                modal.classList.remove('hidden');
                setTimeout(() => modal.classList.remove('opacity-0'), 10);
            }

            function closeModal() {
                modal.classList.add('opacity-0');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }

            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (modal) modal.addEventListener('click', (e) => {
                if(e.target === modal) closeModal();
            });

            async function fetchCalendarData(calendar, year, month) {
                if (calendarGrid) {
                    calendarGrid.innerHTML = '<div class="col-span-7 py-10 text-center text-[#647754]">Memuat data kalender...</div>';
                }

                try {
                    const response = await fetch(`/api/calendars/month?calendar=${calendar}&year=${year}&month=${month}`);
                    if (!response.ok) throw new Error('Gagal memuat data');

                    const result = await response.json();
                    const daysObj = result.days;

                    multiCalendarDays = [];

                    const keys = Object.keys(daysObj);
                    if (keys.length > 0) {
                        const firstDateStr = keys[0];
                        // JS getDay(): 0 = Sunday, 1 = Monday, 6 = Saturday
                        // Our calendar starts on Senin (Monday)
                        let firstDayOfWeek = new Date(firstDateStr).getDay();
                        // Adjust so Monday is 0, Sunday is 6
                        let emptyCells = (firstDayOfWeek + 6) % 7;

                        // Padding start
                        for (let i = 0; i < emptyCells; i++) {
                            multiCalendarDays.push({
                                gregorian: { day: '' },
                                hijri: { day: '' },
                                javanese: { day: '' },
                                sundanese: { day: '' }
                            });
                        }

                        // Populate valid days
                        keys.forEach(dateStr => {
                            const d = daysObj[dateStr];
                            multiCalendarDays.push({
                                gregorian: {
                                    dayName: d.gregorian_day_name || '-',
                                    day: d.gregorian_day,
                                    monthName: d.gregorian_month_name || '-',
                                    monthIndex: d.gregorian_month,
                                    year: d.gregorian_year
                                },
                                hijri: {
                                    dayName: d.hijri_day_name || '-',
                                    day: d.hijri_day || '-',
                                    monthName: d.hijri_month_name || '-',
                                    monthIndex: d.hijri_month,
                                    year: d.hijri_year || '-'
                                },
                                javanese: {
                                    dayName: d.javanese_day_name || '-',
                                    marketDay: d.javanese_market_day || '-',
                                    day: d.javanese_day || '-',
                                    monthName: d.javanese_month_name || '-',
                                    monthIndex: d.javanese_month,
                                    year: d.javanese_year || '-'
                                },
                                sundanese: {
                                    dayName: d.sundanese_day_name || '-',
                                    marketDay: d.sundanese_market_day || '-',
                                    day: d.sundanese_day || '-',
                                    monthName: d.sundanese_month_name || '-',
                                    monthIndex: d.sundanese_month,
                                    year: d.sundanese_year || '-'
                                }
                            });
                        });
                    }

                    renderCalendar();
                } catch (error) {
                    console.error(error);
                    if (calendarGrid) {
                        calendarGrid.innerHTML = '<div class="col-span-7 py-10 text-center text-red-500">Terjadi kesalahan memuat kalender.</div>';
                    }
                }
            }

            function updateVisibleCheckboxes(mainCal) {
                // Hide checkbox for the selected main calendar, show others
                Object.keys(checkContainers).forEach(key => {
                    if (key === mainCal) {
                        checkContainers[key].classList.add('hidden');
                    } else {
                        checkContainers[key].classList.remove('hidden');
                    }
                });
            }

            function populateDropdowns(mainCal) {
                if(!monthSelect || !yearSelect) return;

                // Get month names for the selected calendar type from API data or static if empty
                // But for now, we only need to populate them correctly
                const monthNames = {
                    gregorian: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    hijri: ['Muharram', 'Safar', 'Rabiul Awal', 'Rabiul Akhir', 'Jumadil Awal', 'Jumadil Akhir', 'Rajab', 'Syaban', 'Ramadhan', 'Syawal', 'Dzulqaidah', 'Dzulhijjah'],
                    javanese: ['Sura / Muharam', 'Sapar', 'Mulud', 'Bakda Mulud / Silih Mulud', 'Jumadilawal', 'Jumadilakhir', 'Rejeb', 'Ruwah', 'Pasa (puasa) / Ramelan', 'Sawal / Riyaya', 'Sela / Apit', 'Besar / Rayagung'],
                    sundanese: ['Kasa', 'Karo', 'Katiga / Katelu', 'Kapat', 'Kalima', 'Kanem', 'Kapitu', 'Kawalu / Kawolu', 'Kasanga', 'Kadasa', 'Hapitlemah / Dhesta', 'Hapitkayu / Sadha']
                    // sundanese: ['Kartika', 'Margasira', 'Posya', 'Maga', 'Palguna', 'Setra', 'Wesaka', 'Yesta', 'Asada', 'Srawana', 'Badrapada', 'Asuji']
                };

                const currentMonths = monthNames[mainCal] || monthNames.gregorian;

                monthSelect.innerHTML = '';
                currentMonths.forEach((m, idx) => {
                    const opt = document.createElement('option');
                    opt.value = idx + 1;
                    opt.textContent = m;
                    if (idx + 1 === currentMonth) opt.selected = true;
                    monthSelect.appendChild(opt);
                });

                yearSelect.innerHTML = '';
                // For year, populate +- 10 years around currentYear, bound by 1950-2050 Gregorian equivalent
                for (let y = currentYear - 100; y <= currentYear + 50; y++) {
                    const opt = document.createElement('option');
                    opt.value = y;
                    opt.textContent = y;
                    if (y === currentYear) opt.selected = true;
                    yearSelect.appendChild(opt);
                }
            }

            function renderCalendar() {
                if (!calendarGrid) return;

                const mainCal = mainCalendarSelect.value;

                // Populate Dropdowns only if it's not already correct
                if (monthSelect && monthSelect.value != currentMonth) {
                    populateDropdowns(mainCal);
                }

                if (daysHeader) {
                    daysHeader.innerHTML = '';
                    dayNamesHeader[mainCal].forEach((day, idx) => {
                        const div = document.createElement('div');
                        div.textContent = day;
                        if (idx >= 6) div.classList.add('text-[#87531F]');
                        daysHeader.appendChild(div);
                    });
                }
                const visibleOthers = {
                    gregorian: checkBoxes.gregorian.checked,
                    hijri: checkBoxes.hijri.checked,
                    javanese: checkBoxes.javanese.checked,
                    sundanese: checkBoxes.sundanese.checked
                };

                calendarGrid.innerHTML = '';

                // Get today's date for highlighting (using global todayDate, todayMonth, todayYear)

                multiCalendarDays.forEach((dayData) => {
                    const cell = document.createElement('div');

                    const isToday = (
                        dayData.gregorian.day !== '' &&
                        dayData.gregorian.day === todayDate &&
                        dayData.gregorian.monthIndex === todayMonth &&
                        dayData.gregorian.year === todayYear
                    );

                    if (dayData.gregorian.day === '') {
                        cell.className = "flex flex-col items-center justify-center p-1 md:p-2 min-h-[6rem] md:min-h-[8rem] border border-transparent rounded-xl bg-[#FBFBF7] opacity-50";
                    } else if (isToday) {
                        cell.className = "flex flex-col items-center justify-center p-1 md:p-2 min-h-[6rem] md:min-h-[8rem] border border-[#34402F] rounded-xl transition-colors bg-[#34402F] text-white hover:bg-[#404F3B]";
                    } else {
                        cell.className = "flex flex-col items-center justify-center p-1 md:p-2 min-h-[6rem] md:min-h-[8rem] border border-[#D5DDC8] rounded-xl transition-colors bg-white hover:bg-[#F6F8F3]";
                    }

                    if (dayData.gregorian.day !== '') {
                        cell.classList.add('cursor-pointer');
                        cell.addEventListener('click', () => openModal(dayData, mainCal));

                        const mainDateDiv = document.createElement('div');
                        const subDateDiv = document.createElement('div');
                        subDateDiv.className = "w-full text-left mt-2 hidden md:block";

                        const mainSelected = dayData[mainCal];
                        mainDateDiv.className = isToday
                            ? "text-lg md:text-2xl font-bold text-white"
                            : "text-lg md:text-2xl font-bold text-[#34402F]";
                        mainDateDiv.textContent = mainSelected.day;

                        let subTitle = document.createElement('div');
                        subTitle.className = isToday
                            ? "text-[10px] md:text-xs font-semibold text-[#E5ECDE]"
                            : "text-[10px] md:text-xs font-semibold text-[#647754]";

                        if (mainCal === 'javanese') {
                            subTitle.textContent = `${mainSelected.dayName} ${mainSelected.marketDay}`;
                        } else if (mainCal === 'sundanese') {
                            subTitle.textContent = `${mainSelected.dayName} ${mainSelected.marketDay}`;
                        } else {
                            subTitle.textContent = mainSelected.dayName;
                        }

                        // Add main title below number on mobile, above on desktop
                        const mobileMainTitle = document.createElement('div');
                        mobileMainTitle.className = isToday
                            ? "text-[10px] font-medium text-[#E5ECDE] text-center break-all"
                            : "text-[10px] font-medium text-[#647754] text-center break-all";
                        mobileMainTitle.textContent = `${mainSelected.monthName} ${mainSelected.year}`;

                        // Collect active sub-calendars for Desktop only
                        Object.keys(visibleOthers).forEach(key => {
                            if (key !== mainCal && visibleOthers[key]) {
                                const subItem = document.createElement('div');
                                subItem.className = isToday
                                    ? "text-[9px] md:text-[11px] text-[#D5DDC8] truncate w-full"
                                    : "text-[9px] md:text-[11px] text-[#4F5F43] truncate w-full";
                                subItem.textContent = `${labels[key]}: ${dayData[key].day} ${dayData[key].monthName} ${dayData[key].year}`;
                                subDateDiv.appendChild(subItem);
                            }
                        });

                        const topWrapper = document.createElement('div');
                        topWrapper.className = "flex flex-col items-center w-full relative mb-1";
                        topWrapper.appendChild(mainDateDiv);
                        topWrapper.appendChild(mobileMainTitle);

                        // Hide desktop subtitle if too crowded, or show
                        subTitle.classList.add('hidden', 'absolute', 'top-0', 'left-1');

                        cell.appendChild(subTitle);
                        cell.appendChild(topWrapper);
                        cell.appendChild(subDateDiv);
                    }

                    calendarGrid.appendChild(cell);
                });
                initialLoad = false;
            }

            if (prevMonthBtn && nextMonthBtn) {
                prevMonthBtn.addEventListener('click', () => {
                    currentMonth--;
                    if (currentMonth < 1) {
                        currentMonth = 12;
                        currentYear--;
                    }
                    populateDropdowns(currentCalendar);
                    fetchCalendarData(currentCalendar, currentYear, currentMonth);
                });

                nextMonthBtn.addEventListener('click', () => {
                    currentMonth++;
                    if (currentMonth > 12) {
                        currentMonth = 1;
                        currentYear++;
                    }
                    populateDropdowns(currentCalendar);
                    fetchCalendarData(currentCalendar, currentYear, currentMonth);
                });
            }

            if (monthSelect && yearSelect) {
                monthSelect.addEventListener('change', (e) => {
                    currentMonth = parseInt(e.target.value);
                    fetchCalendarData(currentCalendar, currentYear, currentMonth);
                });
                yearSelect.addEventListener('change', (e) => {
                    currentYear = parseInt(e.target.value);
                    fetchCalendarData(currentCalendar, currentYear, currentMonth);
                });
            }

            const todayBtn = document.getElementById('today-btn');
            if (todayBtn) {
                todayBtn.addEventListener('click', async () => {
                    const mainCal = mainCalendarSelect.value;

                    if (mainCal === 'gregorian') {
                        currentYear = todayYear;
                        currentMonth = todayMonth;
                        populateDropdowns(mainCal);
                        fetchCalendarData(mainCal, currentYear, currentMonth);
                    } else {
                        try {
                            if (calendarGrid) {
                                calendarGrid.innerHTML = '<div class="col-span-7 py-10 text-center text-[#647754]">Memuat data hari ini...</div>';
                            }
                            const response = await fetch(`/api/calendars/month?calendar=gregorian&year=${todayYear}&month=${todayMonth}`);
                            if (!response.ok) throw new Error('Gagal memuat');

                            const result = await response.json();
                            const daysObj = result.days;

                            const todayStr = `${todayYear}-${String(todayMonth).padStart(2, '0')}-${String(todayDate).padStart(2, '0')}`;
                            const todayData = daysObj[todayStr];

                            if (todayData) {
                                currentYear = parseInt(todayData[mainCal + '_year']);
                                currentMonth = parseInt(todayData[mainCal + '_month']);
                            } else {
                                currentYear = todayYear;
                                currentMonth = todayMonth;
                            }

                            populateDropdowns(mainCal);
                            fetchCalendarData(mainCal, currentYear, currentMonth);
                        } catch (e) {
                            console.error(e);
                            currentYear = todayYear;
                            currentMonth = todayMonth;
                            populateDropdowns(mainCal);
                            fetchCalendarData(mainCal, currentYear, currentMonth);
                        }
                    }
                });
            }

            if (mainCalendarSelect) {
                mainCalendarSelect.addEventListener('change', () => {
                    const newCal = mainCalendarSelect.value;

                    // Logic to overlap month based on current grid's middle point
                    const validDays = multiCalendarDays.filter(d => d.gregorian.day !== '');
                    if(validDays.length > 0) {
                        const midDay = validDays[Math.floor(validDays.length / 2)];
                        if(midDay[newCal] && midDay[newCal].year !== '-') {
                            currentYear = parseInt(midDay[newCal].year);
                            currentMonth = parseInt(midDay[newCal].monthIndex);
                        }
                    }

                    currentCalendar = newCal;
                    updateVisibleCheckboxes(currentCalendar);
                    populateDropdowns(currentCalendar);
                    fetchCalendarData(currentCalendar, currentYear, currentMonth);
                });

                Object.values(checkBoxes).forEach(cb => {
                    if (cb) {
                        cb.addEventListener('change', renderCalendar);
                    }
                });

                // Initial render
                updateVisibleCheckboxes(mainCalendarSelect.value);
                populateDropdowns(currentCalendar);
                fetchCalendarData(currentCalendar, currentYear, currentMonth);
            }

            // Conversion Logic
            const convSubmitBtn = document.getElementById('conv-submit-btn');
            if (convSubmitBtn) {
                convSubmitBtn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const fromCal = document.getElementById('conv-from-cal').value;
                    const day = document.getElementById('conv-day').value;
                    const month = document.getElementById('conv-month').value;
                    const year = document.getElementById('conv-year').value;
                    const toCal = document.getElementById('conv-to-cal').value;

                    const errorMsg = document.getElementById('conv-error-msg');
                    errorMsg.classList.add('hidden');

                    if (!day || !month || !year) return;

                    const originalBtnText = convSubmitBtn.innerText;
                    convSubmitBtn.innerText = 'Memuat...';

                    try {
                        const res = await fetch(`/api/calendars/convert?from_calendar=${fromCal}&day=${day}&month=${month}&year=${year}`);
                        const data = await res.json();

                        if (!res.ok) {
                            throw new Error(data.message || 'Gagal mengkonversi');
                        }

                        const d = data.data;

                        // Setup formatted objects for easy mapping
                        const formatted = {
                            gregorian: `${d.gregorian_day_name || ''}, ${d.gregorian_day} ${d.gregorian_month_name || ''} ${d.gregorian_year}`,
                            hijri: `${d.hijri_day_name || ''}, ${d.hijri_day || '-'} ${d.hijri_month_name || '-'} ${d.hijri_year || '-'} H`,
                            javanese: `${d.javanese_day_name || ''} ${d.javanese_market_day || ''}, ${d.javanese_day || '-'} ${d.javanese_month_name || '-'} ${d.javanese_year || '-'} J`,
                            sundanese: `${d.sundanese_day_name || ''} ${d.sundanese_market_day || ''}, ${d.sundanese_day || '-'} ${d.sundanese_month_name || '-'} ${d.sundanese_year || '-'} S`
                        };

                        // Update Main Result
                        const mainTitle = document.getElementById('conv-main-title');
                        const mainText = document.getElementById('conv-main-text');
                        mainTitle.textContent = `Hasil Konversi Utama (${labels[toCal]})`;
                        mainText.textContent = formatted[toCal];

                        // Update Additional Results (the other 3)
                        const others = Object.keys(formatted).filter(k => k !== toCal);

                        document.getElementById('conv-add-1-title').textContent = labels[others[0]];
                        document.getElementById('conv-add-1-text').textContent = formatted[others[0]];

                        document.getElementById('conv-add-2-title').textContent = labels[others[1]];
                        document.getElementById('conv-add-2-text').textContent = formatted[others[1]];

                        document.getElementById('conv-add-3-title').textContent = labels[others[2]];
                        document.getElementById('conv-add-3-text').textContent = formatted[others[2]];

                    } catch (err) {
                        errorMsg.textContent = err.message;
                        errorMsg.classList.remove('hidden');
                    } finally {
                        convSubmitBtn.innerText = originalBtnText;
                    }
                });
            }
        });
    </script>
</body>
</html>
