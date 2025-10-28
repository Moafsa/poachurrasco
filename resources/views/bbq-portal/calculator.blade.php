@extends('layouts.app')

@section('title', 'Calculadora de Churrasco')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="bbq-gradient w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calculator text-white text-3xl"></i>
        </div>
        <h1 class="text-4xl font-bold bbq-text-gradient mb-4">Calculadora de Churrasco</h1>
        <p class="text-lg text-gray-600">Calcule quantidades perfeitas de carne, acompanhamentos e tempo de cocção</p>
    </div>

    <!-- Calculator Form -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <form id="calculator-form" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            
            <!-- Number of People -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-users mr-1"></i> Número de Pessoas
                </label>
                <input type="number" id="people" name="people" min="1" max="100" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500"
                       placeholder="Ex: 8" required>
            </div>

            <!-- Meat Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-drumstick-bite mr-1"></i> Tipo de Carne
                </label>
                <select id="meat_type" name="meat_type" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    <option value="">Selecione o tipo</option>
                    <option value="bovina">Bovina</option>
                    <option value="suina">Suína</option>
                    <option value="frango">Frango</option>
                    <option value="cordeiro">Cordeiro</option>
                    <option value="mista">Mista</option>
                </select>
            </div>

            <!-- Appetite Level -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-utensils mr-1"></i> Nível de Apetite
                </label>
                <select id="appetite_level" name="appetite_level" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brown-500" required>
                    <option value="">Selecione o nível</option>
                    <option value="light">Leve (200g/pessoa)</option>
                    <option value="medium">Médio (300g/pessoa)</option>
                    <option value="heavy">Pesado (400g/pessoa)</option>
                </select>
            </div>

            <!-- Calculate Button -->
            <div class="md:col-span-3 flex justify-center">
                <button type="submit" id="calculate-button" 
                        class="bg-brown-600 text-white px-8 py-3 rounded-lg hover:bg-brown-700 transition-colors disabled:opacity-50">
                    <i class="fas fa-calculator mr-2"></i> Calcular
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div id="results" class="hidden">
        <!-- Main Results -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                <i class="fas fa-chart-pie mr-2"></i> Resultados do Cálculo
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Meat -->
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-drumstick-bite text-brown-600 text-3xl mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Total de Carne</h3>
                    <div class="text-3xl font-bold bbq-text-gradient" id="total-meat">-</div>
                    <p class="text-sm text-gray-600" id="meat-per-person">-</p>
                </div>

                <!-- Cooking Time -->
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-clock text-brown-600 text-3xl mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Tempo Total</h3>
                    <div class="text-3xl font-bold bbq-text-gradient" id="total-time">-</div>
                    <p class="text-sm text-gray-600" id="cooking-time">-</p>
                </div>

                <!-- People -->
                <div class="text-center p-6 bg-gray-50 rounded-lg">
                    <i class="fas fa-users text-brown-600 text-3xl mb-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Pessoas</h3>
                    <div class="text-3xl font-bold bbq-text-gradient" id="total-people">-</div>
                    <p class="text-sm text-gray-600" id="meat-type">-</p>
                </div>
            </div>
        </div>

        <!-- Accompaniments -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                <i class="fas fa-utensils mr-2"></i> Acompanhamentos
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="accompaniments-list">
                <!-- Accompaniments will be populated by JavaScript -->
            </div>
        </div>

        <!-- Cooking Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold bbq-text-gradient mb-6">
                <i class="fas fa-timeline mr-2"></i> Cronograma de Preparo
            </h2>
            
            <div class="space-y-4" id="timeline">
                <!-- Timeline will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Tips -->
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-yellow-800 mb-3">
            <i class="fas fa-lightbulb mr-2"></i> Dicas Importantes
        </h3>
        <ul class="space-y-2 text-yellow-700">
            <li><i class="fas fa-check mr-2"></i> Sempre compre 10-15% a mais de carne para garantir que não falte</li>
            <li><i class="fas fa-check mr-2"></i> Para churrascos longos, considere ter carnes diferentes para variar</li>
            <li><i class="fas fa-check mr-2"></i> Comece a preparar os acompanhamentos 1 hora antes do churrasco</li>
            <li><i class="fas fa-check mr-2"></i> Mantenha a carne na geladeira até 30 minutos antes de grelhar</li>
            <li><i class="fas fa-check mr-2"></i> Tenha sempre sal grosso e temperos extras à mão</li>
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculatorForm = document.getElementById('calculator-form');
    const calculateButton = document.getElementById('calculate-button');
    const results = document.getElementById('results');

    // Handle form submission
    calculatorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(calculatorForm);
        const data = Object.fromEntries(formData);
        
        // Validate form
        if (!data.people || !data.meat_type || !data.appetite_level) {
            showNotification('Por favor, preencha todos os campos', 'error');
            return;
        }

        // Show loading
        calculateButton.disabled = true;
        calculateButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Calculando...';

        // Send request
        fetch('{{ route("recipes.calculator.calculate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayResults(data);
                results.classList.remove('hidden');
                results.scrollIntoView({ behavior: 'smooth' });
            } else {
                showNotification('Erro ao calcular. Tente novamente.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erro ao calcular. Tente novamente.', 'error');
        })
        .finally(() => {
            calculateButton.disabled = false;
            calculateButton.innerHTML = '<i class="fas fa-calculator mr-2"></i> Calcular';
        });
    });

    function displayResults(data) {
        // Main results
        document.getElementById('total-meat').textContent = data.total_kg + ' kg';
        document.getElementById('meat-per-person').textContent = data.grams_per_person + 'g por pessoa';
        document.getElementById('total-time').textContent = data.cooking_time.total_time + ' min';
        document.getElementById('cooking-time').textContent = 'Cocção: ' + data.cooking_time.cooking_time + ' min';
        document.getElementById('total-people').textContent = data.people;
        document.getElementById('meat-type').textContent = data.meat_type.charAt(0).toUpperCase() + data.meat_type.slice(1);

        // Accompaniments
        const accompanimentsList = document.getElementById('accompaniments-list');
        accompanimentsList.innerHTML = '';
        
        Object.entries(data.accompaniments).forEach(([key, item]) => {
            const div = document.createElement('div');
            div.className = 'text-center p-4 bg-gray-50 rounded-lg';
            div.innerHTML = `
                <i class="fas fa-${getAccompanimentIcon(key)} text-brown-600 text-2xl mb-2"></i>
                <h4 class="font-semibold text-gray-900">${item.description}</h4>
                <div class="text-xl font-bold bbq-text-gradient">${item.amount} ${item.unit}</div>
            `;
            accompanimentsList.appendChild(div);
        });

        // Timeline
        const timeline = document.getElementById('timeline');
        timeline.innerHTML = `
            <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-4">1</div>
                <div>
                    <h4 class="font-semibold text-blue-800">Preparação</h4>
                    <p class="text-blue-600">${data.cooking_time.preparation_time} minutos - Tempere a carne e prepare os acompanhamentos</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-red-50 rounded-lg">
                <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center font-bold mr-4">2</div>
                <div>
                    <h4 class="font-semibold text-red-800">Cocção</h4>
                    <p class="text-red-600">${data.cooking_time.cooking_time} minutos - Grelhe a carne no ponto desejado</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-green-50 rounded-lg">
                <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold mr-4">3</div>
                <div>
                    <h4 class="font-semibold text-green-800">Descanso</h4>
                    <p class="text-green-600">${data.cooking_time.resting_time} minutos - Deixe a carne descansar antes de servir</p>
                </div>
            </div>
        `;
    }

    function getAccompanimentIcon(key) {
        const icons = {
            'farofa': 'bread-slice',
            'vinagrete': 'salad',
            'pao_de_alho': 'bread-slice',
            'salada_verde': 'leaf',
            'bebidas': 'glass-martini'
        };
        return icons[key] || 'utensils';
    }
});
</script>
@endsection
