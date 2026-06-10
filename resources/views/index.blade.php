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
            const calendarMonthTitle = document.getElementById('calendar-month-title');
            
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
            let currentYear = new Date().getFullYear();
            let currentMonth = new Date().getMonth() + 1;

            const prevMonthBtn = document.getElementById('prev-month-btn');
            const nextMonthBtn = document.getElementById('next-month-btn');

            async function fetchCalendarData(year, month) {
                if (calendarGrid) {
                    calendarGrid.innerHTML = '<div class="col-span-7 py-10 text-center text-[#647754]">Memuat data kalender...</div>';
                }
                
                try {
                    const response = await fetch(`/api/calendars/month?year=${year}&month=${month}`);
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
                                    year: d.gregorian_year
                                },
                                hijri: {
                                    dayName: d.hijri_day_name || '-',
                                    day: d.hijri_day || '-',
                                    monthName: d.hijri_month_name || '-',
                                    year: d.hijri_year || '-'
                                },
                                javanese: {
                                    dayName: d.javanese_day_name || '-',
                                    marketDay: d.javanese_market_day || '-',
                                    day: d.javanese_day || '-',
                                    monthName: d.javanese_month_name || '-',
                                    year: d.javanese_year || '-'
                                },
                                sundanese: {
                                    sundaneseDayName: d.sundanese_day_name || '-',
                                    marketDay: d.sundanese_market_day || '-',
                                    day: d.sundanese_day || '-',
                                    monthName: d.sundanese_month_name || '-',
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

            function renderCalendar() {
                if (!calendarGrid) return;

                const mainCal = mainCalendarSelect.value;
                
                if (calendarMonthTitle) {
                    // find a valid day in the middle to get the month name
                    const validDay = multiCalendarDays.find(d => d.gregorian.day !== '');
                    if (validDay) {
                        const midDay = validDay[mainCal];
                        calendarMonthTitle.textContent = `${midDay.monthName} ${midDay.year}`;
                    }
                }
                
                if (daysHeader) {
                    daysHeader.innerHTML = '';
                    dayNamesHeader[mainCal].forEach((day, idx) => {
                        const div = document.createElement('div');
                        div.textContent = day;
                        if (idx >= 5) div.classList.add('text-[#87531F]');
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

                // Get today's date for highlighting
                const today = new Date();
                const todayYear = today.getFullYear();
                const todayMonth = today.getMonth() + 1;
                const todayDate = today.getDate();

                multiCalendarDays.forEach((dayData) => {
                    const cell = document.createElement('div');
                    cell.className = "flex flex-col items-center justify-center p-1 md:p-2 min-h-[6rem] md:min-h-[8rem] border border-[#D5DDC8] rounded-xl transition-colors hover:bg-[#F6F8F3]";

                    if (dayData.gregorian.day === '') {
                        cell.classList.add('bg-[#FBFBF7]', 'border-transparent', 'opacity-50');
                    } else {
                        cell.classList.add('bg-white');
                        // Highlight today
                        if (dayData.gregorian.day === todayDate && currentMonth === todayMonth && currentYear === todayYear) {
                            cell.classList.add('bg-[#FFF9EA]', 'ring-1', 'ring-[#EFC765]');
                        }
                    }

                    if (dayData.gregorian.day !== '') {
                        const mainDateDiv = document.createElement('div');
                        const subDateDiv = document.createElement('div');
                        subDateDiv.className = "w-full text-left mt-2 hidden md:block";
                        const subDateMobileDiv = document.createElement('div');
                        subDateMobileDiv.className = "w-full text-center mt-1 md:hidden";

                        const mainSelected = dayData[mainCal];
                        mainDateDiv.className = "text-lg md:text-2xl font-bold text-[#34402F]";
                        mainDateDiv.textContent = mainSelected.day;

                        let subTitle = document.createElement('div');
                        subTitle.className = "text-[10px] md:text-xs font-semibold text-[#647754]";

                        if (mainCal === 'javanese') {
                            subTitle.textContent = `${mainSelected.dayName} ${mainSelected.marketDay}`;
                        } else if (mainCal === 'sundanese') {
                            subTitle.textContent = `${mainSelected.sundaneseDayName} ${mainSelected.marketDay}`;
                        } else {
                            subTitle.textContent = mainSelected.dayName;
                        }

                        // Add main title below number on mobile, above on desktop
                        const mobileMainTitle = document.createElement('div');
                        mobileMainTitle.className = "text-[10px] font-medium text-[#647754]";
                        mobileMainTitle.textContent = `${mainSelected.monthName} ${mainSelected.year}`;

                        // Collect active sub-calendars
                        let activeCount = 0;
                        Object.keys(visibleOthers).forEach(key => {
                            if (key !== mainCal && visibleOthers[key]) {
                                const subItem = document.createElement('div');
                                subItem.className = "text-[9px] md:text-[11px] text-[#4F5F43] truncate w-full";
                                subItem.textContent = `${labels[key]}: ${dayData[key].day} ${dayData[key].monthName} ${dayData[key].year}`;
                                subDateDiv.appendChild(subItem);

                                // Minimal view for mobile
                                if (activeCount < 2) {
                                    const mobileSubItem = document.createElement('div');
                                    mobileSubItem.className = "text-[8px] sm:text-[9px] text-[#7F946D] leading-tight truncate w-full";
                                    mobileSubItem.textContent = `${dayData[key].day} ${dayData[key].monthName} ${dayData[key].year}`;
                                    subDateMobileDiv.appendChild(mobileSubItem);
                                }
                                activeCount++;
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
                        cell.appendChild(subDateMobileDiv);
                    }

                    calendarGrid.appendChild(cell);
                });
            }

            if (prevMonthBtn && nextMonthBtn) {
                prevMonthBtn.addEventListener('click', () => {
                    currentMonth--;
                    if (currentMonth < 1) {
                        currentMonth = 12;
                        currentYear--;
                    }
                    fetchCalendarData(currentYear, currentMonth);
                });

                nextMonthBtn.addEventListener('click', () => {
                    currentMonth++;
                    if (currentMonth > 12) {
                        currentMonth = 1;
                        currentYear++;
                    }
                    fetchCalendarData(currentYear, currentMonth);
                });
            }

            if (mainCalendarSelect) {
                mainCalendarSelect.addEventListener('change', () => {
                    updateVisibleCheckboxes(mainCalendarSelect.value);
                    renderCalendar();
                });

                Object.values(checkBoxes).forEach(cb => {
                    if (cb) {
                        cb.addEventListener('change', renderCalendar);
                    }
                });

                // Initial render
                updateVisibleCheckboxes(mainCalendarSelect.value);
                fetchCalendarData(currentYear, currentMonth);
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
