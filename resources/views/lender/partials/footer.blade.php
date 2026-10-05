{{-- resources/views/lender/partials/footer.blade.php --}}
<footer class="lender-footer">
    <div class="lender-container">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="18" cy="18" r="7" fill="#FFB020" />
                    <circle cx="18" cy="18" r="13.5" stroke="#0F5C6B" stroke-width="1.8" stroke-dasharray="3 4" />
                </svg>
                <span style="font-weight: 700; color: var(--ss-dark);">SolarShare</span>
                <span style="color: var(--ss-slate);">— Plateforme de partage d'équipements solaires et d'énergies renouvelables</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1.5rem; font-size: 0.85rem;">
                <a href="{{ route('lender.dashboard') }}" style="color: var(--ss-slate); text-decoration: none;">Tableau de bord</a>
                <a href="{{ route('equipment.index') }}" style="color: var(--ss-slate); text-decoration: none;">Mes Équipements</a>
                <a href="{{ route('categories.index') }}" style="color: var(--ss-slate); text-decoration: none;">Catégories</a>
                <span style="color: var(--ss-slate-light);">&copy; {{ date('Y') }} SolarShare Tunisie. Tous droits réservés.</span>
            </div>
        </div>
    </div>
</footer>
