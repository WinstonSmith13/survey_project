
  <template>
    <div>
      <h1>{{ associationName }}</h1>
      <p>{{ associationDescription }}</p>
      <h2>Objectifs</h2>
      <ul>
        <li v-for="goal in goals" :key="goal">{{ goal }}</li>
      </ul>
      <h2>Equipes</h2>
      <ul>
        <li v-for="team in teams" :key="team.name">
          <h3>{{ team.name }}</h3>
          <p>Division: {{ team.division }}</p>
        </li>
      </ul>
      <h2>Matchs à venir</h2>
      <ul>
        <li v-for="match in upcomingMatches" :key="match.id">
          <p>{{ match.date }}</p>
          <p>{{ match.homeTeam }} vs {{ match.awayTeam }}</p>
        </li>
      </ul>
      <h2>Contact</h2>
      <p>Email: {{ contactEmail }}</p>
      <p>Téléphone: {{ contactPhone }}</p>
    </div>
  </template>


<script>

export default {
  data() {
    return {
      associationName: 'Nom de l\'association',
      associationDescription: 'Description de l\'association',
      goals: [
        'Objectif 1',
        'Objectif 2',
        'Objectif 3'
      ],
      teams: [
        { name: 'Equipe 1', division: 'Division 1' },
        { name: 'Equipe 2', division: 'Division 2' },
        { name: 'Equipe 3', division: 'Division 3' }
      ],
      upcomingMatches: [
        { id: 1, date: '10/01/2022', homeTeam: 'Equipe 1', awayTeam: 'Equipe 2' },
        { id: 2, date: '17/01/2022', homeTeam: 'Equipe 3', awayTeam: 'Equipe 1' },
        { id: 3, date: '24/01/2022', homeTeam: 'Equipe 2', awayTeam: 'Equipe 3' }
      ],
      contactEmail: 'contact@association.com',
      contactPhone: '555-555-5555'
    }
  }
}

</script>


