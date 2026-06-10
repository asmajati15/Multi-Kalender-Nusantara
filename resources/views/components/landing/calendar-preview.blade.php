<section id="multi-calendar" class="py-24 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4 text-[#34402F]">Tampilan Multi Calendar</h2>
            <p class="text-lg text-[#647754]">Bandingkan tanggal Masehi, Hijriyah, <span class="line-through decoration-red-500 decoration-[2px] opacity-70">Jawa, dan Sunda</span> dalam satu grid kalender yang rapi.</p>
        </div>

        <div class="mb-8 flex flex-col md:flex-row items-center justify-between bg-[#FBFBF7] p-4 rounded-2xl border border-[#D5DDC8] gap-4">
            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-[#4F5F43] whitespace-nowrap">Kalender Utama:</label>
                <select id="main-calendar-select" class="flex h-10 w-40 rounded-md border border-[#D5DDC8] bg-white px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765]">
                    <option value="gregorian">Masehi</option>
                    <option value="hijri">Hijriyah</option>
                    <option value="javanese" disabled>Jawa (Coming Soon)</option>
                    <option value="sundanese" disabled>Sunda (Coming Soon)</option>
                </select>
            </div>

            <div class="flex items-center gap-3 flex-wrap justify-center">
                <span class="text-sm font-medium text-[#4F5F43]">Tampilkan Detail:</span>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" id="show-hijri" checked class="rounded border-[#D5DDC8] text-[#7F946D] focus:ring-[#7F946D]" />
                    <span class="text-[#34402F]">Hijriyah</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" id="show-javanese" checked disabled class="rounded border-[#D5DDC8] text-[#7F946D] focus:ring-[#7F946D]" />
                    <span class="text-[#34402F]">Jawa</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" id="show-sundanese" checked disabled class="rounded border-[#D5DDC8] text-[#7F946D] focus:ring-[#7F946D]" />
                    <span class="text-[#34402F]">Sunda</span>
                </label>
                <label class="flex items-center gap-2 text-sm cursor-pointer hidden" id="show-gregorian-container">
                    <input type="checkbox" id="show-gregorian" checked class="rounded border-[#D5DDC8] text-[#7F946D] focus:ring-[#7F946D]" />
                    <span class="text-[#34402F]">Masehi</span>
                </label>
            </div>
        </div>

        <div class="rounded-3xl border border-[#D5DDC8] bg-white text-card-foreground shadow-sm overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-6 border-b border-[#D5DDC8]">
                <button id="prev-month-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765] border border-[#D5DDC8] bg-[#F6F8F3] hover:bg-[#D5DDC8] text-[#34402F] h-10 w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <div class="font-bold text-lg md:text-xl text-[#34402F]" id="calendar-month-title">
                    Juni 2026
                </div>
                <button id="next-month-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765] border border-[#D5DDC8] bg-[#F6F8F3] hover:bg-[#D5DDC8] text-[#34402F] h-10 w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>

            <div class="p-4 md:p-6 bg-[#FBFBF7]/50">
                <!-- Days of week -->
                <div id="calendar-days-header" class="grid grid-cols-7 mb-2 text-center text-sm font-bold text-[#647754]">
                    <div>Senin</div>
                    <div>Selasa</div>
                    <div>Rabu</div>
                    <div>Kamis</div>
                    <div>Jumat</div>
                    <div class="text-[#87531F]">Sabtu</div>
                    <div class="text-[#87531F]">Minggu</div>
                </div>

                <!-- Calendar Grid -->
                <div id="calendar-grid" class="grid grid-cols-7 gap-1 md:gap-2">
                    <!-- Javascript will populate this -->
                </div>
            </div>
        </div>
    </div>
</section>
