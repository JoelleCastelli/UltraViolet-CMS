# CMS

# To Launch the projet :

1. run `docker-compose up -d` in the root directory
2. run `npm install` in `www/webpack` to get node dependancies
3. run `npm run build` also in `www/webpack` to build the front located in `www/dist` directory
   ?. (TODO: installation script for the MCS)

# Memo pour s'inscrire en attendant la prod :

1. Mettre en bdd un media à l'id 1, titlde default, et path /src/img
2. Lors de l'inscrition, dans le controller person, déommenter le set default picture
3. Mettre statiquement setMediaId a 1
4. Mettre le setEmailConfirmed à 1
5. Si ça marche, on est inscrit, il suffit de discard les changements dans git maintenant
