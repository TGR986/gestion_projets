<x-app-layout>
    <x-slot name="header">
        <h2>Ajouter un participant</h2>
    </x-slot>

    <div class="p-6 max-w-3xl">
        <p style="margin-bottom: 20px;">
            <strong>Projet :</strong> {{ $projet->intitule }}
        </p>

        @if ($errors->any())
            <div style="color:red; border:1px solid red; padding:10px; margin-bottom:15px;">
                <strong>Erreurs :</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('projets.participants.store', $projet) }}">
            @csrf

            <div style="margin-bottom:15px;">
                <label for="utilisateur_id"><strong>Utilisateur</strong></label><br>
                <select name="utilisateur_id" id="utilisateur_id" style="width:100%; max-width:500px;">
                    <option value="">-- Choisir un utilisateur --</option>
                    @foreach($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->id }}" {{ old('utilisateur_id') == $utilisateur->id ? 'selected' : '' }}>
                            {{ $utilisateur->name }} ({{ $utilisateur->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:15px;">
                <label for="fonction_projet"><strong>Fonction dans le projet</strong></label><br>
                <input type="text"
                       name="fonction_projet"
                       id="fonction_projet"
                       value="{{ old('fonction_projet') }}"
                       style="width:100%; max-width:500px;">
            </div>

            <div style="margin-bottom:20px;">
                <label>
                    <input type="checkbox" name="est_chef_projet" value="1" {{ old('est_chef_projet') ? 'checked' : '' }}>
                    Chef de projet
                </label>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit">Ajouter le participant</button>
                <a href="{{ route('projets.show', $projet) }}">Annuler</a>
            </div>
        </form>
    </div>
</x-app-layout>