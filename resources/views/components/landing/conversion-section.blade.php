<section id="konversi" class="py-24 bg-[#FBFBF7]">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4 text-[#34402F]">Konversi Tanggal Antar Kalender</h2>
            <p class="text-lg text-[#647754]">Pilih sistem kalender asal dan tujuan untuk mengonversi tanggal Masehi, Hijriyah, <span class="line-through decoration-red-500 decoration-[2px] opacity-70">Jawa, atau Sunda</span> dalam satu tempat.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 items-start">
            <!-- Form Card -->
            <div class="rounded-3xl border border-[#D5DDC8] bg-white/85 text-card-foreground shadow-sm backdrop-blur">
                <div class="flex flex-col space-y-1.5 p-6 border-b border-[#D5DDC8]/50">
                    <h3 class="font-semibold leading-none tracking-tight text-[#34402F]">Form Konversi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none text-[#4F5F43]">Kalender Asal</label>
                        <select id="conv-from-cal" class="flex h-10 w-full rounded-md border border-[#D5DDC8] bg-white px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765]">
                            <option value="gregorian">Masehi</option>
                            <option value="hijri">Hijriyah</option>
                            <option value="javanese">Jawa/Sunda-Islam</option>
                            <option value="sundanese" disabled>Saka/Matahari (Coming Soon)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none text-[#4F5F43]">Tanggal / Bulan / Tahun</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input id="conv-day" type="number" placeholder="Tgl" value="10" class="flex h-10 w-full rounded-md border border-[#D5DDC8] bg-white px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765]" />
                            <select id="conv-month" class="flex h-10 w-full rounded-md border border-[#D5DDC8] bg-white px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765]">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6" selected>Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                            <input id="conv-year" type="number" placeholder="Thn" value="2026" class="flex h-10 w-full rounded-md border border-[#D5DDC8] bg-white px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765]" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none text-[#4F5F43]">Kalender Tujuan</label>
                        <select id="conv-to-cal" class="flex h-10 w-full rounded-md border border-[#D5DDC8] bg-white px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765]">
                            <option value="hijri">Hijriyah</option>
                            <option value="gregorian">Masehi</option>
                            <option value="javanese">Jawa/Sunda-Islam</option>
                            <option value="sundanese" disabled>Saka/Matahari (Coming Soon)</option>
                        </select>
                    </div>

                    <button id="conv-submit-btn" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#EFC765] bg-[#7F946D] text-[#FFF9EA] hover:bg-[#647754] h-10 px-4 py-2 w-full mt-2">
                        Konversi Sekarang
                    </button>
                </div>
            </div>

            <!-- Result Card Area -->
            <div class="space-y-4 h-full flex flex-col justify-center">
                <!-- Main Result -->
                <div class="rounded-3xl border border-[#EFC765]/60 bg-[#FFF9EA]/70 text-card-foreground shadow-sm flex flex-col justify-center p-6 text-center">
                    <h3 id="conv-main-title" class="font-medium tracking-tight text-[#87531F] mb-3">Hasil Konversi Utama (Hijriyah)</h3>
                    <div id="conv-main-text" class="text-2xl md:text-3xl font-bold text-[#603A1E]">
                    </div>
                </div>

                <!-- Additional Results Grid -->
                <div id="conv-additional-results" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
                    <div class="rounded-2xl border border-[#D5DDC8] bg-white p-4 text-center">
                        <h4 id="conv-add-1-title" class="text-xs font-semibold text-[#647754] mb-1 uppercase tracking-wider">Masehi</h4>
                        <p id="conv-add-1-text" class="text-sm font-medium text-[#34402F]"></p>
                    </div>
                    <div class="rounded-2xl border border-[#D5DDC8] bg-white p-4 text-center">
                        <h4 id="conv-add-2-title" class="text-xs font-semibold text-[#647754] mb-1 uppercase tracking-wider">Jawa/Sunda-Islam</h4>
                        <p id="conv-add-2-text" class="text-sm font-medium text-[#34402F]"></p>
                    </div>
                    <div class="rounded-2xl border border-[#D5DDC8] bg-white p-4 text-center">
                        <h4 id="conv-add-3-title" class="text-xs font-semibold text-[#647754] mb-1 uppercase tracking-wider">Saka/Matahari</h4>
                        <p id="conv-add-3-text" class="text-sm font-medium text-[#34402F] opacity-50">Coming Soon</p>
                    </div>
                </div>

                <p id="conv-error-msg" class="text-sm text-red-500 mt-2 italic text-center hidden"></p>
            </div>
        </div>
    </div>
</section>
