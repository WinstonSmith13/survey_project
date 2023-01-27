
<template>

  <div class="bg-white mx-auto  max-w-7xl py-6 px-4 sm:px-6 lg:px-8 shadow-md p-10 text-left flex flex-col ">
    <h1 class="text-2xl font-medium">Qui sommes nous ?</h1>
    <p class="text-base text-gray-600 leading-relaxed">{{ associationDescription }}</p>
    <h2 class="text-xl font-medium mt-4">Objectifs</h2>
    <p class="text-base text-gray-600 leading-relaxed">{{ associationGoal }}</p>
    <h2 class="text-xl  font-medium mt-4">Activités</h2>
    <ul class="list-disc pl-4">
      <li v-for="activity in activities" :key="activity" class="text-base text-gray-600 leading-relaxed">{{ activity }}
      </li>
    </ul>
    <h2 class="text-xl  font-medium mt-4">Affiliations</h2>
    <ul class="list-disc pl-4">
      <li v-for="affiliation in affiliations" :key="affiliation" class="text-base text-gray-600 leading-relaxed">{{
        affiliation
      }}</li>
    </ul>
    <h2 class="text-xl font-medium mt-4">Promotion de l'intégration</h2>
    <p class="text-base text-gray-600 leading-relaxed">{{ integrationPromotion }}</p>
  </div>
</template>


<script>
export default {
  data() {
    return {
      associationDescription: 'L’association MUST (Marseille United – Sport pour Tous, association loi 1901) a été créée en janvier 2013, par un groupe d’amis qui voulaient former un groupe de footballeurs LGBT-friendly. Suite au succès de cette initiative et à l’intérêt porté à d’autres sports, l’association s’est diversifiée avec 17 activités que nous proposons aujourd’hui !',
      associationGoal: 'L’objectif de l’association est de permettre aux adhérents de faire du sport de manière conviviale, en acceptant chacune et chacun, et indépendamment du niveau sportif. Notre credo : chacun a ses propres limites mais notre envie nous pousse à devenir meilleur·e·s.',
      activities: [
        'Activité 1',
        'Activité 2',
        'Activité 3'
      ],
      affiliations: [
        'Fédération 1',
        'Fédération 2',
        'Fédération 3'
      ],
      integrationPromotion: 'MUST promeut l’intégration de toutes et tous, quelles que soient leurs différences. Bien que sa mission première concerne l’égalité homme/femme dans le sport et l’intégration LGBT, les partenariats créés depuis 2013 concernent des populations des plus diversifiées.'
    }
  }
}
</script>




