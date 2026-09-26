<div class="space-y-6">
    {{-- Exporter --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Exporter les données</h3>
        <p class="text-gray-600 mb-6">Téléchargez une copie complète de vos données</p>
        
        <div class="grid grid-cols-2 gap-4">
            <button class="p-6 rounded-2xl border-2 border-gray-200 hover:border-cyan-500 hover:bg-cyan-50 transition-all text-left">
                <div class="text-3xl mb-2">📊</div>
                <p class="font-semibold text-gray-900">Exporter en Excel</p>
                <p class="text-sm text-gray-500 mt-1">Format XLSX</p>
            </button>

            <button class="p-6 rounded-2xl border-2 border-gray-200 hover:border-cyan-500 hover:bg-cyan-50 transition-all text-left">
                <div class="text-3xl mb-2">📄</div>
                <p class="font-semibold text-gray-900">Exporter en CSV</p>
                <p class="text-sm text-gray-500 mt-1">Format texte</p>
            </button>

            <button class="p-6 rounded-2xl border-2 border-gray-200 hover:border-cyan-500 hover:bg-cyan-50 transition-all text-left">
                <div class="text-3xl mb-2">📋</div>
                <p class="font-semibold text-gray-900">Exporter en PDF</p>
                <p class="text-sm text-gray-500 mt-1">Rapport complet</p>
            </button>

            <button class="p-6 rounded-2xl border-2 border-gray-200 hover:border-cyan-500 hover:bg-cyan-50 transition-all text-left">
                <div class="text-3xl mb-2">💾</div>
                <p class="font-semibold text-gray-900">Sauvegarde complète</p>
                <p class="text-sm text-gray-500 mt-1">Base de données</p>
            </button>
        </div>
    </div>

    {{-- Importer --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Importer des données</h3>
        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-12 text-center hover:border-cyan-500 hover:bg-cyan-50 transition-all cursor-pointer">
            <div class="text-5xl mb-4">📤</div>
            <p class="font-semibold text-gray-900 mb-2">Glissez un fichier ici</p>
            <p class="text-sm text-gray-500">ou cliquez pour parcourir</p>
            <p class="text-xs text-gray-400 mt-2">Formats supportés : XLSX, CSV, JSON</p>
        </div>
    </div>
</div>