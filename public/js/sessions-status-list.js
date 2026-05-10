(function () {
    function init() {
        const calendarTitle = document.getElementById('status-calendar-title');
        const calendarGrid = document.getElementById('status-calendar-grid');
        const calendarPrev = document.getElementById('status-calendar-prev');
        const calendarNext = document.getElementById('status-calendar-next');
        const dateFilter = document.getElementById('session-date-filter');
        const statusFilter = document.getElementById('session-status-filter');
        const emergencyFilter = document.getElementById('session-emergency-filter');
        const clearDateButton = document.getElementById('session-clear-date');
        const tableBody = document.getElementById('session-table-body');
        if (!calendarTitle || !calendarGrid || !calendarPrev || !calendarNext || !dateFilter || !statusFilter || !tableBody) return;
        const rows = Array.from(tableBody.querySelectorAll('tr[data-session-date]'));
        const noResultsRow = document.getElementById('no-results-row');
        const visibleCount = document.getElementById('visible-count');
        const approvedCount = document.getElementById('approved-count');
        const completedCount = document.getElementById('completed-count');
        const notePopup = document.getElementById('session-note-popup');
        const notePopupClose = document.getElementById('close-session-note-popup');
        const noteStudent = document.getElementById('session-note-student');
        const noteTopic = document.getElementById('session-note-topic');
        const noteContent = document.getElementById('session-note-content');
        const monthLabel = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' });
        const bookedDates = new Set(rows.map(r => r.dataset.sessionDate).filter(Boolean));
        let selectedDate = ''; let currentMonthDate = new Date();
        function renderCalendar() { const y = currentMonthDate.getFullYear(), m = currentMonthDate.getMonth(); const first = new Date(y, m, 1), off = first.getDay(), days = new Date(y, m + 1, 0).getDate(); calendarTitle.textContent = monthLabel.format(first); calendarGrid.innerHTML = ''; for (let i = 0; i < off; i++) { const d = document.createElement('div'); d.className = 'h-10 border-r border-b border-slate-100 bg-slate-50/60'; calendarGrid.appendChild(d); } for (let day = 1; day <= days; day++) { const date = new Date(y, m, day), iso = date.toISOString().slice(0, 10), wd = date.getDay(); const btn = document.createElement('button'); btn.type = 'button'; btn.dataset.date = iso; btn.textContent = String(day); const weekend = wd === 0 || wd === 6, sel = selectedDate === iso, has = bookedDates.has(iso); btn.className = ['h-10 border-r border-b border-slate-100 text-sm transition-all duration-200', weekend ? 'bg-slate-50 text-slate-300 cursor-not-allowed' : 'bg-white text-slate-700 hover:bg-sky-50', sel ? 'bg-sky-600 font-semibold text-white' : '', has && !sel && !weekend ? 'font-semibold text-emerald-700 ring-1 ring-emerald-100' : ''].join(' ').trim(); if (!weekend) { btn.addEventListener('click', () => { selectedDate = selectedDate === iso ? '' : iso; dateFilter.value = selectedDate; updateRows(); renderCalendar(); }); } else { btn.disabled = true; } calendarGrid.appendChild(btn); } }
        function updateRows() { const status = statusFilter.value, emer = (emergencyFilter && emergencyFilter.value) || ''; let visible = 0, approved = 0, completed = 0; rows.forEach(row => { const okDate = !selectedDate || row.dataset.sessionDate === selectedDate, okStatus = !status || row.dataset.sessionStatus === status, okEmer = !emer || (row.dataset.sessionEmergency || 'normal') === emer, show = okDate && okStatus && okEmer; row.classList.toggle('hidden', !show); if (show) { visible++; if (row.dataset.sessionStatus === 'approved') approved++; if (row.dataset.sessionStatus === 'completed') completed++; } }); if (noResultsRow) noResultsRow.classList.toggle('hidden', !(rows.length > 0 && visible === 0)); if (visibleCount) visibleCount.textContent = String(visible); if (approvedCount) approvedCount.textContent = String(approved); if (completedCount) completedCount.textContent = String(completed); }
        statusFilter.addEventListener('change', updateRows); emergencyFilter && emergencyFilter.addEventListener('change', updateRows);
        calendarPrev.addEventListener('click', () => { currentMonthDate = new Date(currentMonthDate.getFullYear(), currentMonthDate.getMonth() - 1, 1); renderCalendar(); });
        calendarNext.addEventListener('click', () => { currentMonthDate = new Date(currentMonthDate.getFullYear(), currentMonthDate.getMonth() + 1, 1); renderCalendar(); });
        clearDateButton && clearDateButton.addEventListener('click', () => { selectedDate = ''; dateFilter.value = ''; statusFilter.value = ''; if (emergencyFilter) emergencyFilter.value = ''; updateRows(); renderCalendar(); });
        document.querySelectorAll('.note-view-btn').forEach(btn => btn.addEventListener('click', () => { if (!notePopup) return; noteStudent.textContent = btn.dataset.student || '-'; noteTopic.textContent = btn.dataset.topic || '-'; noteContent.textContent = btn.dataset.note || 'No note provided.'; notePopup.classList.remove('hidden'); notePopup.classList.add('flex'); }));
        const close = () => { notePopup && notePopup.classList.add('hidden'); notePopup && notePopup.classList.remove('flex'); };
        notePopupClose && notePopupClose.addEventListener('click', close); notePopup && notePopup.addEventListener('click', e => { if (e.target === notePopup) close(); });
        updateRows(); renderCalendar();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
