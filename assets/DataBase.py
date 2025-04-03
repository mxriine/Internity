from datetime import datetime, timedelta
import mysql.connector
import requests
import random
import hashlib

# 🔹 Configuration
json_url = "https://static.data.gouv.fr/resources/villes-de-france/20220928-173621/cities.json"

# Récupérer le contenu du fichier JSON
response = requests.get(json_url)

# Vérifier si la requête a réussi
if response.status_code == 200:
    data = response.json()  # Décoder directement en JSON
else:
    print(f"Erreur lors de la récupération des données: {response.status_code}")
    exit()

# 🔹 Liste officielle des 18 régions françaises
regions_officielles = {
    "Auvergne-Rhône-Alpes",
    "Bourgogne-Franche-Comté",
    "Bretagne",
    "Centre-Val de Loire",
    "Corse",
    "Grand Est",
    "Hauts-de-France",
    "Île-de-France",
    "Normandie",
    "Nouvelle-Aquitaine",
    "Occitanie",
    "Pays de la Loire",
    "Provence-Alpes-Côte d'Azur",
    "Guadeloupe",
    "Martinique",
    "Guyane",
    "La Réunion",
    "Mayotte"
}

# 🔹 Liste des entreprises à insérer
companies = [
    ("TotalEnergies", "Une des principales compagnies pétrolières et gazières mondiales.", "Énergie", "contact@totalenergies.com", "+33 1 47 44 45 46", 0.00, "Courbevoie"),
    ("AXA", "Leader mondial de l'assurance et de la gestion d'actifs.", "Assurances", "service.client@axa.com", "+33 1 40 75 57 00", 0.00, "Paris"),
    ("Carrefour", "L'un des plus grands groupes de distribution au monde.", "Distribution", "contact@carrefour.com", "+33 1 41 04 26 00", 0.00, "Massy"),
    ("Crédit Agricole", "Première banque en France et l'une des plus grandes en Europe.", "Banque", "relation.client@credit-agricole.com", "+33 1 43 23 52 02", 0.00, "Montrouge"),
    ("BNP Paribas", "Groupe bancaire international majeur.", "Banque", "contact@bnpparibas.com", "+33 1 40 14 45 46", 0.00, "Paris"),
    ("EDF", "Principal producteur et fournisseur d'électricité en France.", "Énergie", "service.client@edf.fr", "+33 1 40 42 22 22", 0.00, "Paris"),
    ("Engie", "Groupe énergétique mondial spécialisé dans les énergies renouvelables.", "Énergie", "contact@engie.com", "+33 1 44 22 00 00", 0.00, "Courbevoie"),
    ("Renault", "Constructeur automobile français de renommée mondiale.", "Automobile", "service.client@renault.com", "+33 1 76 84 04 04", 0.00, "Boulogne-Billancourt"),
    ("Stellantis", "Groupe automobile multinational issu de la fusion de PSA et FCA.", "Automobile", "contact@stellantis.com", "+33 1 70 96 00 00", 0.00, "Vélizy-Villacoublay"),
    ("CMA CGM", "L'un des leaders mondiaux du transport maritime.", "Transport maritime", "contact@cma-cgm.com", "+33 4 88 91 90 00", 0.00, "Marseille"),
    ("Vinci", "Groupe mondial de concessions et de construction.", "Construction", "contact@vinci.com", "+33 1 47 16 35 00", 0.00, "Rueil-Malmaison"),
    ("Bouygues", "Groupe diversifié dans la construction, les médias et les télécoms.", "Construction, Télécoms", "contact@bouygues.com", "+33 1 44 20 10 00", 0.00, "Paris"),
    ("LVMH", "Leader mondial de l'industrie du luxe.", "Luxe", "contact@lvmh.com", "+33 1 44 13 22 22", 0.00, "Paris"),
    ("Société Générale", "L'une des principales banques européennes.", "Banque", "service.client@socgen.com", "+33 1 42 14 20 00", 0.00, "Paris"),
    ("Saint-Gobain", "Leader mondial des matériaux de construction.", "Matériaux", "contact@saint-gobain.com", "+33 1 47 62 30 00", 0.00, "Courbevoie"),
    ("Veolia", "Spécialiste mondial de la gestion optimisée des ressources.", "Services", "contact@veolia.com", "+33 1 85 57 70 00", 0.00, "Paris"),
    ("Sanofi", "Leader mondial de la santé et des vaccins.", "Pharmaceutique", "contact@sanofi.com", "+33 1 53 77 40 00", 0.00, "Paris"),
    ("Auchan", "Groupe de distribution présent dans plusieurs pays.", "Distribution", "contact@auchan.com", "+33 3 20 65 50 00", 0.00, "Croix"),
    ("Airbus", "Leader mondial de l'aéronautique et de l'espace.", "Aéronautique", "contact@airbus.com", "+33 5 61 93 33 33", 0.00, "Blagnac"),
    ("Orange", "Opérateur de télécommunications majeur en Europe.", "Télécommunications", "service.client@orange.com", "+33 1 44 44 22 22", 0.00, "Issy-les-Moulineaux"),
    ("Alstom", "Spécialiste mondial des systèmes de transport intégrés.", "Transport", "contact@alstom.com", "+33 1 57 06 90 00", 0.00, "Saint-Ouen-sur-Seine"),
    ("Danone", "Leader mondial des produits laitiers et de l'eau en bouteille.", "Agroalimentaire", "contact@danone.com", "+33 1 44 35 20 20", 0.00, "Paris"),
    ("Michelin", "L'un des principaux fabricants de pneumatiques au monde.", "Pneumatiques", "contact@michelin.com", "+33 4 73 32 20 00", 0.00, "Clermont-Ferrand"),
    ("Sodexo", "Leader mondial des services de qualité de vie.", "Services", "contact@sodexo.com", "+33 1 30 85 75 00", 0.00, "Issy-les-Moulineaux"),
    ("Air Liquide", "Leader mondial des gaz industriels.", "Gaz", "contact@airliquide.com", "+33 1 40 62 55 55", 0.00, "Paris"),
    ("Thales", "Leader mondial des technologies de pointe.", "Électronique", "contact@thalesgroup.com", "+33 1 57 77 80 00", 0.00, "Paris"),
    ("Pernod Ricard", "Co-leader mondial des vins et spiritueux.", "Spiritueux", "contact@pernod-ricard.com", "+33 1 41 00 41 00", 0.00, "Paris"),
    ("Kering", "Groupe mondial du luxe.", "Luxe", "contact@kering.com", "+33 1 45 64 61 00", 0.00, "Paris"),
    ("Dassault Aviation", "Constructeur aéronautique français.", "Aéronautique", "contact@dassault-aviation.com", "+33 1 47 11 40 00", 0.00, "Saint-Cloud"),
    ("Hermès", "Maison française de haute couture et de luxe.", "Luxe", "contact@hermes.com", "+33 1 40 17 47 17", 0.00, "Paris")
]

# 🔹 Liste des utilisateurs à insérer
users = [
    ("GOAT", "thegreatest@nasa.com", "Toto", 
     "2589480a812b6de5d733efad7d379b3b469ced031a54df0a3bb175a5bdc60f3c7120c5ff6095ff929713a99380656654ded73d8d8170e36647ea2f6abd587a31",
     "/////", "/////"),
    ("MAZOU", "mazou.marine@lego.com", "Marine", 
     "bb61c39e6b19e7326bbaacc59ac71fcff8ce01f0feee0c7d46b2c18a56f915bbd9506430adf055dd11c857cbd6192cac0e97a02600bad8e221a0f1f462f279e5",
     "/////", "/////"),
    ("JOURDAN", "nolan.jourdan@nvidia.com", "Nolan", 
     "e08844e3071a4843be37db88cd04d41c2be0e2d38bdcdb8ca4107afea9999534db977381156ffc96dbd0777fd7ec3934cf4810d12c87e1e590579a6072025a2d",
     "/////", "/////"),
    ("GUERTON", "guerton.nathan@orange.fr", "Nathan", 
     "6a25b62ee832d31579312749aecaba03666c13d45aff15a946603eda02762f8af421c53d223e41ab4dc49013610ac537b3fcb97d15375489dc2c692130441118",
     "/////", "/////"),
    ("KAMDJOU", "hmkamdjou@tesla.com", "Hugues-Marie", 
     "a7a1a7b09c304c9059fbe9438eda68000a62282d93f1444519f2aba7a287c67710c334763cf64eee16748b9dcc6f4d6e62e42e5376c093ce68af0b79847f29cc",
     "/////", "/////"),
    ("RAYNAUD", "mraynaud@huawei.com", "Muriel", 
     "9d3cb9e40b27b8ecf400de7df1a58fb00e920fb09f9a8375ae907aa58fdd697fdae7c7921127210a474a339798fcf94dbd6658675e7eed76ed9f7d00ef2abc93",
     "/////", "/////"),
    ("ARNAUD", "maarnaud@mas-btp.com", "Marc-Alexandre", 
     "33862db008801430cd71fb6add28de9fc6d72269f726bb2f2b670ee4b2e54861d3786723adaf9c65d98d835ffdf3922dde9c39735c893bd868d223938a7d3d76",
     "/////", "/////"),
    ("BELLINGHAM", "jbellingham@total.com", "Jude", 
     "bd9a193c2f6cc532bfe6b8d7423d288588f379cc9fe3feae08433b2adf462b8e3c74ff48388e75a3140da40cc5f9a4c436dd9b5cfc719b3f0da2205037adea6c",
     "/////", "/////"),
    ("MCGREGOR", "mcgregor@ufc.com", "Conor", 
     "4b9617653b97dfd65e771f434ab2b74dd43a72f90a00340e95346a4258d2f6143b92b3974f0358ab0c8829c76e2c9ebf43381f51cb8491d792c025101d190e90",
     "/////", "/////"),
    ("TYSON", "mike.tyson@yahoo.com", "Mike", 
     "6afd6c6b7d76ad68509716ad72afb7c73ee495034510d3045d69e2cb4048094819f0a76ccfe66309972db724569f3b32aac08425e37b03996e55acf5b9f4e208",
     "/////", "/////"),
    ("MESSI", "leo@fcb.com", "Lionel",
     "f9459a75c7611ba037ec27b47ac65c81c292c2f2958b2937018a7a0c351d1a833fdab859b8c7e14a70ce8fdc0171275d6d3788a74c7dffd9a4af9a1a3e4278e2",
     "/////", "/////"),
    ("RONALDO", "cr7@alnassr.com", "Cristiano",
     "8e71dbd6504c2900e4ee8b04c8a34855d8b951789ed6f4c7e375b6353418cda32befd3356ddc12b385b271b294739b280fe6d0716cec8b8c9d1380d21857f463",
     "/////", "/////"),
    ("ZUCKERBERG", "mark@meta.com", "Mark",
     "0f9f091430f0661f1b106ddce21562e753919987dbd8247cd57caa34f02299f945a181e94435ec742a1fe29a930f2c495e46aa6f56d89f020ea53fef6c68cb55",
     "/////", "/////"),
    ("MUSK", "elon@spacex.com", "Elon",
     "75700f2ec789a7b43097abb7265f5ce060e1d6fe13d10a990302c267ad2661a177454376e6b2fe391d6ef185161f38f575113f9a45611f65a5195cacd94d9be2",
     "/////", "/////"),
    ("BEZOS", "jeff@amazon.com", "Jeff",
     "aa9bd35d495fd0a256f1fbbc4abea0aacb2e9aacdd78c369bf0935a88b19e81760d1bebabc8d09b05b1f9f7083f71dd9a16a4db10a212f7375ac759f4a4261aa",
     "/////", "/////"),
    ("GATES", "bill@microsoft.com", "Bill",
     "19660481567501a5aa20523695f8fec6b48da49c164a08c5a96d67e336a999ab945be7395b1341884d41ded0a64455655a7d99e7d7c38c5b26670fe2729b2622",
     "/////", "/////"),
    ("BRYANT", "kobe@nba.com", "Kobe",
     "c0a72c22a87b9a987d4aa5c6bbf8329ad41d8f36b75b0717b56979072b4b498f27652f88834861185ecc301b43c76495adfee9fcca7739bf5d791480b9ac9bcb",
     "/////", "/////"),
    ("JORDAN", "michael@nba.com", "Michael",
     "0e9210a705c341c5e7ec1a127dceec33c2103049b5f00c5156a3c9a42234490bbd55147c84a6e631337bde749e8303118611129cf867c8fd4e306c11f1845b13",
     "/////", "/////"),
    ("BRANSON", "richard@virgin.com", "Richard",
     "28541b58127c71df2da01cb7d1ae9517148c1de12087cc91c1421feb788c83c861c2eab6b4ee446f8750c634df5a0da9e536e7e0f2fe07ee837b94bb9877224a",
     "/////", "/////"),
    ("COOK", "tim@apple.com", "Tim",
     "7de56c0146545d3addedbb98e479d8cdfda3f002d7134936f5588f7ee41b8c998225249f36e3ef89967cb1b25b322e432cf739160667bfb66bcba286aeb14c98",
     "/////", "/////"),
    ("BUFFETT", "warren@berkshire.com", "Warren",
     "d06f6d017b526a99cc6f9510e4860049b50c68437047ebff4cd1c642539021c739db163696e38a118747061514435bd02911ad4d577aac79d865380a10d6315d",
     "/////", "/////"),
    ("DJOKOVIC", "novak@atp.com", "Novak",
     "0540c16542d094fb11074f0a7e40287d4aeb75df35b9c161aa9ed5069e765c3964e445c699973aeb3e654180e6677081d0ed90eba4577154dbf9739161a54335",
     "/////", "/////"),
    ("FEDERER", "roger@atp.com", "Roger",
     "2fb354fdb0dc06197120ab77e160f95bbe14aff636da0b7d88b15687c09742034256001bfa62ada4baef43d3d03699b5f85eeddfabea4c88fc9d5ed61981da38",
     "/////", "/////"),
    ("NADAL", "rafa@atp.com", "Rafael",
     "fc2c45ee359ed7f52890c00655fbfcf3b06d092ca6225ebb8a57739cf0e4c698a5d8595f784ddb59c758d374aea482c3620373396144dd83f3f503db642b8bd3",
     "/////", "/////"),
    ("WOODS", "tiger@golf.com", "Tiger",
     "2b202f4b67b556af1ea6a47ca42d1f6590bc31117736eaf2f6d01d6027f9e78a0837295b5823c8834915978397cdd3b1134ec78b724dcb49c6f99f82e1b2a1de",
     "/////", "/////"),
    ("HAMILTON", "lewis@f1.com", "Lewis",
     "cacb4da78e3a68904c026a3ad000eedc9bed046694ac45ace269aa3637663fe40ba5764c2a0d88f6a831792207e109187902b9e2ea03c04357dbab54b8673b35",
     "/////", "/////"),
    ("VERSTAPPEN", "max@redbull.com", "Max",
     "952ebfd9e088b8e564f6e0d64dd7e9bdbcfcde686f711ef423419a4eecdb6deb67718b3ac5ed36e4695fd680912f2b3b8281a88670727175a178f5d9095de971",
     "/////", "/////"),
    ("BOLT", "usain@olympics.com", "Usain",
     "0f05e117d7232d4aee93bfe8fc6f2edaa7648d9298a00726694ad614f7a53e4db556486ecafebb6874ee58de3d6e1543824a9a693e4a80b62c8bcc99805a8b63",
     "/////", "/////"),
    ("BRYANT", "kobe@nba.com", "Kobe",
     "c0a72c22a87b9a987d4aa5c6bbf8329ad41d8f36b75b0717b56979072b4b498f27652f88834861185ecc301b43c76495adfee9fcca7739bf5d791480b9ac9bcb",
     "/////", "/////"),
    ("JORDAN", "michael@nba.com", "Michael",
     "0e9210a705c341c5e7ec1a127dceec33c2103049b5f00c5156a3c9a42234490bbd55147c84a6e631337bde749e8303118611129cf867c8fd4e306c11f1845b13",
     "/////", "/////"),
    ("MESSI", "leo@fcb.com", "Lionel",
     "f9459a75c7611ba037ec27b47ac65c81c292c2f2958b2937018a7a0c351d1a833fdab859b8c7e14a70ce8fdc0171275d6d3788a74c7dffd9a4af9a1a3e4278e2",
     "/////", "/////"),
    ("RONALDO", "cr7@alnassr.com", "Cristiano",
     "8e71dbd6504c2900e4ee8b04c8a34855d8b951789ed6f4c7e375b6353418cda32befd3356ddc12b385b271b294739b280fe6d0716cec8b8c9d1380d21857f463",
     "/////", "/////"),
    ("HABRIOUX", "mhabrioux@cesi.fr", "Matthieu",
     "cfaa06175d239e7c162a7f25fd616004c0311e9c69f6e6003728a084f8a67483f854bf765e2f33eefb897e400e09b2a0f10883ebb302a899bf7e7bdccbde298c",
     "/////", "/////"),
     ("BRANSON", "richard@virgin.com", "Richard",
     "28541b58127c71df2da01cb7d1ae9517148c1de12087cc91c1421feb788c83c861c2eab6b4ee446f8750c634df5a0da9e536e7e0f2fe07ee837b94bb9877224a",
     "/////", "/////"),
    ("COOK", "tim@apple.com", "Tim",
     "7de56c0146545d3addedbb98e479d8cdfda3f002d7134936f5588f7ee41b8c998225249f36e3ef89967cb1b25b322e432cf739160667bfb66bcba286aeb14c98",
     "/////", "/////"),
    ("BUFFETT", "warren@berkshire.com", "Warren",
     "d06f6d017b526a99cc6f9510e4860049b50c68437047ebff4cd1c642539021c739db163696e38a118747061514435bd02911ad4d577aac79d865380a10d6315d",
     "/////", "/////"),
    ("DJOKOVIC", "novak@atp.com", "Novak",
     "0540c16542d094fb11074f0a7e40287d4aeb75df35b9c161aa9ed5069e765c3964e445c699973aeb3e654180e6677081d0ed90eba4577154dbf9739161a54335",
     "/////", "/////"),
    ("FEDERER", "roger@atp.com", "Roger",
     "2fb354fdb0dc06197120ab77e160f95bbe14aff636da0b7d88b15687c09742034256001bfa62ada4baef43d3d03699b5f85eeddfabea4c88fc9d5ed61981da38",
     "/////", "/////"),
    ("NADAL", "rafa@atp.com", "Rafael",
     "fc2c45ee359ed7f52890c00655fbfcf3b06d092ca6225ebb8a57739cf0e4c698a5d8595f784ddb59c758d374aea482c3620373396144dd83f3f503db642b8bd3",
     "/////", "/////"),
    ("WOODS", "tiger@golf.com", "Tiger",
     "2b202f4b67b556af1ea6a47ca42d1f6590bc31117736eaf2f6d01d6027f9e78a0837295b5823c8834915978397cdd3b1134ec78b724dcb49c6f99f82e1b2a1de",
     "/////", "/////"),
    ("HAMILTON", "lewis@f1.com", "Lewis",
     "cacb4da78e3a68904c026a3ad000eedc9bed046694ac45ace269aa3637663fe40ba5764c2a0d88f6a831792207e109187902b9e2ea03c04357dbab54b8673b35",
     "/////", "/////"),
    ("VERSTAPPEN", "max@redbull.com", "Max",
     "952ebfd9e088b8e564f6e0d64dd7e9bdbcfcde686f711ef423419a4eecdb6deb67718b3ac5ed36e4695fd680912f2b3b8281a88670727175a178f5d9095de971",
     "/////", "/////"),
    ("BOLT", "usain@olympics.com", "Usain",
     "0f05e117d7232d4aee93bfe8fc6f2edaa7648d9298a00726694ad614f7a53e4db556486ecafebb6874ee58de3d6e1543824a9a693e4a80b62c8bcc99805a8b63",
     "/////", "/////"),
    ("BRYANT", "kobe@nba.com", "Kobe",
     "c0a72c22a87b9a987d4aa5c6bbf8329ad41d8f36b75b0717b56979072b4b498f27652f88834861185ecc301b43c76495adfee9fcca7739bf5d791480b9ac9bcb",
     "/////", "/////"),
    ("JORDAN", "michael@nba.com", "Michael",
     "0e9210a705c341c5e7ec1a127dceec33c2103049b5f00c5156a3c9a42234490bbd55147c84a6e631337bde749e8303118611129cf867c8fd4e306c11f1845b13",
     "/////", "/////"),
    ("MESSI", "leo@fcb.com", "Lionel",
     "f9459a75c7611ba037ec27b47ac65c81c292c2f2958b2937018a7a0c351d1a833fdab859b8c7e14a70ce8fdc0171275d6d3788a74c7dffd9a4af9a1a3e4278e2",
     "/////", "/////"),
    ("RONALDO", "cr7@alnassr.com", "Cristiano",
     "8e71dbd6504c2900e4ee8b04c8a34855d8b951789ed6f4c7e375b6353418cda32befd3356ddc12b385b271b294739b280fe6d0716cec8b8c9d1380d21857f463",
     "/////", "/////"),
    ("HABRIOUX", "mhabrioux@cesi.fr", "Matthieu",
     "cfaa06175d239e7c162a7f25fd616004c0311e9c69f6e6003728a084f8a67483f854bf765e2f33eefb897e400e09b2a0f10883ebb302a899bf7e7bdccbde298c",
     "/////", "/////")
]

# Transformer le hash
for user in users:
    user = list(user)  # Convert tuple to list to allow modification
    user[3] = hashlib.sha512(user[1].encode()).hexdigest()

# 🔹 Liste des promotions à insérer
promotions = [
    {
        "promotion_name" : "CPI A1",
        "promotion_desc" : "Promotion CPI A1 - 1ère Année"
    },
    {
        "promotion_name" : "CPI A2 Info",
        "promotion_desc" : "Promotion CPI A2 - 2ème Année - SPécialité Informatique"
    },
    {
        "promotion_name" : "CPI A2 Géné",
        "promotion_desc" : "Promotion CPI A2 - 2ème Année - Spécialité Généraliste"
    },
    {
        "promotion_name" : "Inge A3 Info",
        "promotion_desc" : "Promotion Ingénieur - 1ème Année - Spécialité Informatique"
    },
    {
        "promotion_name" : "Inge A3 Géné",
        "promotion_desc" : "Promotion Ingénieur - 1ème Année - Spécialité Généraliste"
    }
]

# 🔹 Liste des offres à insérer
offers = [
    # TotalEnergies (ID 1)
    {
        "offer_title": "Ingénieur Énergies Renouvelables - Stage",
        "offer_desc": """Nous recherchons un stagiaire motivé pour rejoindre notre département R&D en énergies solaires. Missions principales :
- Participation à l'optimisation des systèmes photovoltaïques
- Analyse des données de performance des installations
- Support technique pour les projets d'innovation durable
- Étude de faisabilité pour l'intégration de stockage batterie
Profil recherché : Élève-ingénieur en énergétique, maitrise de Python et outils de simulation. Anglais technique requis.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=95),
        "company_id": 1
    },
    {
        "offer_title": "Analyste Data Énergie - Stage",
        "offer_desc": """Au sein de notre direction digitale, vous contribuerez à :
- Développer des algorithmes de prédiction de consommation
- Automatiser le reporting des indicateurs clés
- Créer des dashboards interactifs pour les opérationnels
- Participer à des projets Big Data sur l'efficacité énergétique
Compétences requises : SQL, Power BI, connaissance du secteur énergétique. Stage de 6 mois avec projets concrets.""",
        "offer_salary": 1550.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 1
    },

    # AXA (ID 2)
    {
        "offer_title": "Stagiaire Actuariat - Risk Modelling",
        "offer_desc": """Intégrez notre équipe actuariat pour :
- Développer des modèles de prédiction des sinistres
- Analyser les données historiques d'assurance auto
- Participer à l'élaboration des tarifications
- Automatiser des processus de calcul de risques
Profil : École d'ingénieurs/actuariat, compétences en statistiques (R/Python) et solide niveau d'anglais.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=7),
        "offer_end": datetime.now() + timedelta(days=127),
        "company_id": 2
    },
    {
        "offer_title": "Stage Digital Innovation Assurtech",
        "offer_desc": """Contribuez à la transformation digitale AXA :
- Prototyper des solutions IoT pour l'assurance habitation
- Analyser les données des objets connectés
- Participer à des hackathons internes
- Benchmark des nouvelles technologies insurtech
Profil créatif avec double compétence tech/business. Expérience en UX appréciée.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=110),
        "company_id": 2
    },

    # Carrefour (ID 3)
    {
        "offer_title": "Stage Data Analyst - Supply Chain",
        "offer_desc": """Au sein de notre direction logistique :
- Optimiser les prévisions de demande grâce au ML
- Analyser les flux d'approvisionnement internationaux
- Développer des indicateurs de performance logistique
- Participer à la digitalisation des processus
Profil : Data Science/Logistique, Python avancé, connaissances en supply chain.""",
        "offer_salary": 1500.00,
        "offer_start": datetime.now() + timedelta(days=15),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 3
    },
    {
        "offer_title": "Stagiaire Marketing Digital",
        "offer_desc": """Rejoignez notre équipe e-commerce :
- Analyser les performances des campagnes online
- Optimiser le parcours client sur l'app mobile
- Participer à des A/B tests sur les promotions
- Élaborer des recommandations stratégiques
Compétences : Google Analytics, connaissance retail, esprit analytique.""",
        "offer_salary": 1450.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 3
    },

    # Crédit Agricole (ID 4)
    {
        "offer_title": "Stage Cybersécurité Financière",
        "offer_desc": """Au sein de notre direction des risques :
- Auditer les processus d'authentification
- Participer à des tests d'intrusion contrôlés
- Développer des outils de détection d'anomalies
- Sensibiliser les équipes aux bonnes pratiques
Profil : École d'ingénieurs en sécurité informatique, connaissances en finance.""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=20),
        "offer_end": datetime.now() + timedelta(days=140),
        "company_id": 4
    },
    {
        "offer_title": "Analyste Fintech - Stage",
        "offer_desc": """Contribuez à notre innovation bancaire :
- Étudier l'impact des cryptomonnaies
- Benchmark des solutions blockchain
- Modéliser des cas d'usage pour la banque retail
- Participer à des POC avec des startups fintech
Profil curieux avec forte appétence pour les nouvelles technologies financières.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 4
    },

    # BNP Paribas (ID 5)
    {
        "offer_title": "Stage Quantitative Finance",
        "offer_desc": """Au sein de notre desk de trading :
- Développer des modèles de pricing d'options
- Optimiser les algorithmes de couverture
- Analyser les données de marché en temps réel
- Implémenter des solutions de risque de crédit
Profil : Mathématiques appliquées, Python/C++, connaissance produits dérivés.""",
        "offer_salary": 1800.00,
        "offer_start": datetime.now() + timedelta(days=30),
        "offer_end": datetime.now() + timedelta(days=150),
        "company_id": 5
    },
    {
        "offer_title": "Stagiaire Conformité AML",
        "offer_desc": """Au sein du département compliance :
- Automatiser les contrôles KYC
- Analyser les transactions suspectes
- Développer des outils de monitoring
- Participer à la mise à jour des procédures
Profil : Juriste/Ingénieur avec intérêt pour la régulation financière.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=100),
        "company_id": 5
    },

    # EDF (ID 6)
    {
        "offer_title": "Ingénieur Nucléaire - Stage",
        "offer_desc": """Au sein de notre centrale de Flamanville :
- Participer à des études de sûreté
- Analyser les données de fonctionnement
- Optimiser les procédures de maintenance
- Contribuer à des projets d'innovation technique
Profil : École d'ingénieurs en génie nucléaire, rigueur extrême, aptitude au travail en équipe.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 6
    },
    {
        "offer_title": "Stage Smart Grids",
        "offer_desc": """Rejoignez notre équipe R&D réseaux électriques :
- Modéliser des scénarios de charge électrique
- Développer des algorithmes de gestion de microgrids
- Analyser les données des compteurs intelligents
- Participer à des expérimentations terrain
Compétences : Python, Power Systems, curiosité pour la transition énergétique.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 6
    },

    # Engie (ID 7)
    {
        "offer_title": "Stage Ingénieur Biométhane",
        "offer_desc": """Au sein de notre direction énergies renouvelables :
- Optimiser les processus de méthanisation
- Analyser la qualité du biogaz produit
- Participer à des études de faisabilité
- Développer des outils de monitoring
Profil : Génie des procédés, connaissance énergies vertes, autonomie.""",
        "offer_salary": 1550.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=125),
        "company_id": 7
    },
    {
        "offer_title": "Data Analyst Énergie - Stage",
        "offer_desc": """Contribuez à notre plateforme data :
- Analyser les données de consommation clients
- Développer des modèles prédictifs
- Créer des datavisualisations pour les décideurs
- Automatiser des rapports complexes
Compétences : SQL, Python, Power BI. Appétence pour le secteur énergétique.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=110),
        "company_id": 7
    },

    # Renault (ID 8)
    {
        "offer_title": "Stage Ingénieur Motorisations Électriques",
        "offer_desc": """Au sein de notre centre R&D :
- Participer à l'optimisation des performances batteries
- Analyser des données de tests d'endurance
- Contribuer à des projets d'éco-conception
- Développer des outils de simulation thermique
Profil : Génie électrique/mécanique, MATLAB/Simulink, anglais technique.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=20),
        "offer_end": datetime.now() + timedelta(days=140),
        "company_id": 8
    },
    {
        "offer_title": "Stagiaire UX Automotive",
        "offer_desc": """Rejoignez notre équipe interface utilisateur :
- Concevoir des prototypes d'écrans véhicule
- Participer à des tests utilisateurs
- Analyser les tendances du marché
- Développer des maquettes interactives
Compétences : Figma/Adobe XD, ergonomie, créativité. Passion automobile appréciée.""",
        "offer_salary": 1500.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 8
    },

    # Stellantis (ID 9)
    {
        "offer_title": "Stage Ingénieur Automobile – Développement de Prototypes",
        "offer_desc": """Intégrez notre équipe R&D pour :
- Développer des prototypes de véhicules innovants
- Optimiser l’aérodynamique et la consommation énergétique
- Collaborer avec des équipes pluridisciplinaires
Profil : Étudiant en ingénierie automobile, passionné par l’innovation.""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=12),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 9
    },
    {
        "offer_title": "Stage Ingénieur Mécanique – Optimisation de Processus",
        "offer_desc": """Rejoignez notre département d’ingénierie pour :
- Analyser et améliorer les chaînes de production
- Mettre en place des solutions innovantes en conception mécanique
- Collaborer étroitement avec les équipes de R&D
Profil : Étudiant en mécanique, rigueur et esprit analytique.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 9
    },

    # CMA CGM (ID 10)
    {
        "offer_title": "Stage Analyste Logistique Maritime",
        "offer_desc": """Rejoignez notre département logistique pour :
- Analyser les flux maritimes
- Optimiser la chaîne d'approvisionnement
- Contribuer à la digitalisation des opérations portuaires
Profil : Bac+3/5 en logistique, maîtrise d'Excel et SQL.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=8),
        "offer_end": datetime.now() + timedelta(days=128),
        "company_id": 10
    },
    {
        "offer_title": "Stage Chef de Projet Logistique",
        "offer_desc": """Intégrez notre équipe pour :
- Coordonner des projets d'optimisation logistique
- Superviser l'implémentation de solutions digitales
- Analyser les performances des opérations portuaires
Profil : Formation en logistique et management, sens de l'organisation.""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=9),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 10
    },

    # Vinci (ID 11)
    {
        "offer_title": "Stage Ingénieur Construction Durable",
        "offer_desc": """Participez à des projets d'infrastructure innovants :
- Concevoir des solutions de construction écologiques
- Collaborer avec des bureaux d'études pour optimiser les structures
- Effectuer des simulations de performances énergétiques
Profil : Formation en génie civil, compétences en logiciels de CAO.""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now() + timedelta(days=15),
        "offer_end": datetime.now() + timedelta(days=145),
        "company_id": 11
    },
    {
        "offer_title": "Stage Architecte en BTP – Construction Innovante",
        "offer_desc": """Intégrez notre bureau d'études afin de :
- Réaliser des plans pour des projets durables
- Innover dans l'utilisation de matériaux écologiques
- Assurer le suivi des travaux et la conformité des normes
Profil : Étudiant en architecture ou BTP, créatif et autonome.""",
        "offer_salary": 1800.00,
        "offer_start": datetime.now() + timedelta(days=12),
        "offer_end": datetime.now() + timedelta(days=140),
        "company_id": 11
    },
    {
        "offer_title": "Stage Assistant Bureau d'Études – Structures Écologiques",
        "offer_desc": """Collaborez avec notre équipe d'études pour :
- Participer à l'élaboration de plans innovants
- Réaliser des simulations de structures durables
- Contribuer à l'optimisation énergétique des bâtiments
Profil : Bac+3 en génie civil ou architecture, motivé par le développement durable.""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=138),
        "company_id": 11
    },

    # Bouygues (ID 12)
    {
        "offer_title": "Stage Ingénieur Télécoms et Réseaux",
        "offer_desc": """Intégrez notre division télécoms :
- Développer des solutions de communication avancées
- Optimiser l'architecture réseau
- Participer à la modernisation des infrastructures
Profil : Étudiant en télécommunications, connaissances en fibre optique et protocoles réseaux.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 12
    },
    {
        "offer_title": "Stage Ingénieur Support Infrastructure IT",
        "offer_desc": """Rejoignez notre département IT pour :
- Assurer le support et la maintenance des réseaux
- Contribuer à l'implémentation de solutions de connectivité
- Participer à la sécurisation des infrastructures
Profil : Bac+3 en informatique ou télécommunications, autonomie et rigueur.""",
        "offer_salary": 1550.00,
        "offer_start": datetime.now() + timedelta(days=8),
        "offer_end": datetime.now() + timedelta(days=115),
        "company_id": 12
    },

    # LVMH (ID 13)
    {
        "offer_title": "Stage Marketing et Communication Luxe",
        "offer_desc": """Participez à des stratégies marketing innovantes :
- Élaborer des campagnes de communication haut de gamme
- Collaborer avec des équipes créatives pour le branding
- Analyser les tendances du marché du luxe
Profil : Bac+3/5 en marketing, passion pour le secteur du luxe.""",
        "offer_salary": 1800.00,
        "offer_start": datetime.now() + timedelta(days=7),
        "offer_end": datetime.now() + timedelta(days=110),
        "company_id": 13
    },
    {
        "offer_title": "Stage Gestion de Produit – Mode et Luxe",
        "offer_desc": """Rejoignez notre équipe pour :
- Participer à la conception et au lancement de nouveaux produits
- Analyser le positionnement et la performance des collections
- Travailler en étroite collaboration avec les équipes design et marketing
Profil : Formation en marketing ou management du luxe, créativité et sens commercial.""",
        "offer_salary": 1850.00,
        "offer_start": datetime.now() + timedelta(days=9),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 13
    },

    # Société Générale (ID 14)
    {
        "offer_title": "Stage Finance Quantitative et Analyse des Risques",
        "offer_desc": """Intégrez notre équipe de finance :
- Développer des modèles mathématiques pour l'analyse des risques
- Participer à des projets de data science appliquée à la finance
- Collaborer avec des traders et analystes financiers
Profil : Formation en finance ou mathématiques appliquées, maîtrise de Python et SQL.""",
        "offer_salary": 1900.00,
        "offer_start": datetime.now() + timedelta(days=14),
        "offer_end": datetime.now() + timedelta(days=150),
        "company_id": 14
    },
    {
        "offer_title": "Stage Analyste Financier – Modélisation et Stratégie",
        "offer_desc": """Rejoignez notre département d'analyse pour :
- Assister à la modélisation financière et la prévision des risques
- Contribuer à l'élaboration de stratégies d'investissement
- Analyser les données de marché et produire des rapports détaillés
Profil : Bac+5 en finance ou économie, esprit analytique et passion pour les chiffres.""",
        "offer_salary": 1950.00,
        "offer_start": datetime.now() + timedelta(days=16),
        "offer_end": datetime.now() + timedelta(days=155),
        "company_id": 14
    },

    # Saint-Gobain (ID 15)
    {
        "offer_title": "Stage R&D Matériaux Innovants",
        "offer_desc": """Au sein de notre laboratoire de recherche :
- Caractériser les propriétés mécaniques de nouveaux matériaux
- Participer à des essais de durabilité
- Optimiser des formulations de verre intelligent
- Analyser des résultats microscopiques
Profil : Matériaux/Chimie, rigueur expérimentale, esprit d'innovation.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 15
    },
    {
        "offer_title": "Stage Industrie 4.0",
        "offer_desc": """Contribuez à la digitalisation d'usine :
- Implémenter des capteurs IoT sur les lignes
- Analyser les données de production
- Développer des indicateurs OEE
- Participer à des projets d'automatisation
Compétences : Python, IoT, connaissance processus industriels.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 15
    },

    # Veolia (ID 16)
    {
        "offer_title": "Stage Traitement des Eaux",
        "offer_desc": """Au sein de notre direction technique :
- Optimiser les processus d'épuration
- Analyser la qualité des eaux traitées
- Participer à des études d'impact
- Développer des outils de prédiction de pollution
Profil : Génie chimique/environnement, passion écologie.""",
        "offer_salary": 1550.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 16
    },
    {
        "offer_title": "Stage Data Analyst - Recyclage",
        "offer_desc": """Rejoignez notre équipe data :
- Développer des algorithmes de tri intelligent
- Analyser l'efficacité du recyclage
- Créer des dashboards de suivi écologique
Compétences : Python, SQL, volonté de contribuer à l'économie circulaire.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=110),
        "company_id": 16
    },

    # Sanofi (ID 17)
    {
        "offer_title": "Stage R&D Pharmaceutique",
        "offer_desc": """Au sein de notre laboratoire :
- Participer à la recherche de nouveaux médicaments
- Analyser des données cliniques
- Contribuer à des études de formulation
- Développer des outils de modélisation
Profil : Pharmacie/Chimie, rigueur scientifique, esprit d'équipe.""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=8),
        "offer_end": datetime.now() + timedelta(days=128),
        "company_id": 17
    },
    {
        "offer_title": "Stage Data Science Santé",
        "offer_desc": """Rejoignez notre équipe data :
- Développer des algorithmes de prédiction de maladies
- Analyser des données de santé publique
- Participer à des projets d'IA pour la santé
- Collaborer avec des équipes pluridisciplinaires
Compétences : Python, R, connaissance secteur santé.""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 17
    },
    {
        "offer_title": "Stage Informatique Biomédicale",
        "offer_desc": """Au sein de notre équipe informatique :
- Développer des applications pour la gestion des données patients
- Participer à l'intégration de systèmes d'information
- Analyser les flux de données cliniques
- Contribuer à des projets de cybersécurité
Profil : Informatique/Santé, compétences en développement logiciel, rigueur.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=12),
        "offer_end": datetime.now() + timedelta(days=140),
        "company_id": 17
    },

    # Auchan (ID 18)
    {
        "offer_title": "Stage Data Analyst - Retail",
        "offer_desc": """Rejoignez notre équipe data :
- Analyser les données de vente
- Développer des modèles de prévision de stock
- Optimiser les promotions en ligne
- Participer à des projets de data visualisation
Compétences : SQL, Python, connaissance secteur retail.""",
        "offer_salary": 1500.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 18
    },
    {
        "offer_title": "Stagiaire Marketing Digital - E-commerce",
        "offer_desc": """Contribuez à notre stratégie digitale :
- Analyser les performances des campagnes marketing
- Optimiser l'expérience utilisateur sur notre site
- Participer à des projets d'innovation digitale
- Collaborer avec des équipes créatives
Compétences : Google Analytics, SEO, passion pour le e-commerce.""",
        "offer_salary": 1450.00,
        "offer_start": datetime.now() + timedelta(days=7),
        "offer_end": datetime.now() + timedelta(days=110),
        "company_id": 18
    },
    {
        "offer_title": "Responsable Logistique - CDI",
        "offer_desc": """Rejoignez notre équipe logistique :
- Superviser les opérations d'approvisionnement
- Optimiser les flux de marchandises
- Gérer une équipe de collaborateurs
- Analyser les indicateurs de performance
Profil : Bac+5 en logistique ou management, expérience en gestion d'équipe.""",
        "offer_salary": 3500.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=365),
        "company_id": 18
    },
    {
        "offer_title": "Chef de Projet IT - CDI",
        "offer_desc": """Intégrez notre département IT :
- Piloter des projets de transformation digitale
- Collaborer avec les équipes métiers et techniques
- Assurer le respect des délais et budgets
- Proposer des solutions innovantes pour le retail
Profil : Bac+5 en informatique ou gestion de projet, expérience en IT.""",
        "offer_salary": 4000.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=365),
        "company_id": 18
    },

    # Airbus (ID 19)
    {
        "offer_title": "Stage Ingénierie Aéronautique",
        "offer_desc": """Au sein du bureau d'études :
- Participer à des simulations aérodynamiques
- Analyser des résultats de tests en soufflerie
- Optimiser des structures composites
- Contribuer à des études de réduction de poids
Profil : École d'ingénieurs aéronautique, passion pour l'aviation.""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=15),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 19
    },
    {
        "offer_title": "Stagiaire Cybersécurité Avionique",
        "offer_desc": """Au sein de la direction technique :
- Évaluer les vulnérabilités des systèmes embarqués
- Participer à des audits de sécurité
- Développer des prototypes de protection
- Analyser les normes DO-326A/ED-202
Profil : Sécurité informatique, connaissance systèmes critiques.""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 19
    },
    {
        "offer_title": "Stage Data Science Aéronautique",
        "offer_desc": """Rejoignez notre équipe data :
- Développer des algorithmes de maintenance prédictive
- Analyser des données de vol
- Participer à des projets d'optimisation de la consommation
- Collaborer avec des équipes pluridisciplinaires
Profil : Data Science, Python/R, passion pour l'aéronautique.""",
        "offer_salary": 1800.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 19
    },

    # Orange (ID 20) - 3 offres
    {
        "offer_title": "Ingénieur Réseaux 5G - Stage",
        "offer_desc": """Au sein de la direction technique :
- Participer au déploiement de nouvelles antennes 5G
- Analyser les performances réseau avec des outils de monitoring
- Optimiser les paramètres de qualité de service
- Contribuer à des tests d'interopérabilité
Profil : Télécommunications, connaissance protocoles réseaux, Python""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=125),
        "company_id": 20
    },
    {
        "offer_title": "Stage Cybersécurité Opérateur",
        "offer_desc": """Protection des infrastructures critiques :
- Surveillance des tentatives d'intrusion
- Développement d'outils de détection d'anomalies
- Audit des configurations réseau
- Participation à des exercices Red Team/Blue Team
Compétences : SOC, SIEM, forte appétence sécurité""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 20
    },
    {
        "offer_title": "Data Analyst IoT - Stage",
        "offer_desc": """Optimisation des objets connectés :
- Analyse des données de consommation énergétique
- Développement de modèles prédictifs de maintenance
- Visualisation des KPI pour les smart cities
- Intégration de données temps réel
Outils : Spark, Kafka, Power BI""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 20
    },

    # Alstom (ID 21) - 3 offres
    {
        "offer_title": "Ingénieur Signalisation Ferroviaire",
        "offer_desc": """Modernisation des systèmes ERTMS :
- Modélisation des interfaces train/voie
- Participation aux tests de validation
- Analyse des données de sécurité
- Documentation technique
Profil : Systèmes embarqués, normes ferroviaires""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now() + timedelta(days=7),
        "offer_end": datetime.now() + timedelta(days=127),
        "company_id": 21
    },
    {
        "offer_title": "Stage Éco-Conception Trains",
        "offer_desc": """Réduction de l'empreinte carbone :
- Analyse cycle de vie des matériaux
- Simulation de l'aérodynamique des rames
- Étude de matériaux recyclés
- Benchmark solutions bas carbone
Compétences : CAD, écoconception, passion mobilité durable""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=110),
        "company_id": 21
    },
    {
        "offer_title": "Analyste Maintenance Prédictive",
        "offer_desc": """Optimisation de la disponibilité :
- Collecte données capteurs embarqués
- Développement algorithmes d'usure
- Intégration dans l'outil de GMAO
- Analyse coût-bénéfice
Outils : Python, SQL, Spark""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=15),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 21
    },

    # Danone (ID 22) - 3 offres
    {
        "offer_title": "Stage Nutrition Sensorielle",
        "offer_desc": """Innovation produits laitiers :
- Tests consommateur en laboratoire
- Analyse statistique des préférences
- Développement de nouveaux profils aromatiques
- Étude de texture au rhéomètre
Profil : Agroalimentaire, statistiques, curiosité foodtech""",
        "offer_salary": 1550.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 22
    },
    {
        "offer_title": "Ingénieur Procédés Laitiers",
        "offer_desc": """Optimisation de production :
- Étude des paramètres de pasteurisation
- Réduction des pertes matière première
- Automatisation de lignes d'emballage
- Analyse HACCP
Compétences : Génie des procédés, sécurité alimentaire""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 22
    },
    {
        "offer_title": "Data Analyst Supply Chain",
        "offer_desc": """Gestion des flux logistiques :
- Prédiction des ruptures de stock
- Optimisation des tournées de livraison
- Analyse des données fournisseurs
- Dashboard de suivi KPI
Outils : Power BI, Python, SAP""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=125),
        "company_id": 22
    },

    # Michelin (ID 23)
    {
        "offer_title": "Stage R&D Matériaux Polymères",
        "offer_desc": """Dans notre centre de recherche :
- Caractériser les propriétés d'usure des gommes
- Optimiser des formulations de composés
- Participer à des essais sur bancs roulants
- Analyser des données de durabilité
Profil : Matériaux/Chimie, curiosité technologique, rigueur scientifique.""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 23
    },
    {
        "offer_title": "Stage Industrie 4.0 - Usine Intelligente",
        "offer_desc": """Contribuez à la transformation digitale :
- Implémenter des solutions IoT de monitoring
- Analyser les données de production
- Développer des algorithmes de maintenance prédictive
- Participer à des projets d'automatisation
Compétences : Python, Data Analysis, connaissance processus industriels.""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 23
    },

    # Air Liquide (ID 24) - 3 offres
    {
        "offer_title": "Ingénieur Cryogénie - Stage",
        "offer_desc": """Développement de systèmes basse température :
- Calculs thermodynamiques avancés
- Tests de matériaux à -196°C
- Optimisation des processus de liquéfaction
- Sécurité des installations
Profil : Génie thermique, rigueur expérimentale""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now() + timedelta(days=20),
        "offer_end": datetime.now() + timedelta(days=140),
        "company_id": 24
    },
    {
        "offer_title": "Stage Hydrogène Énergie",
        "offer_desc": """Développement de la mobilité H2 :
- Étude des piles à combustible
- Logistique de distribution
- Analyse des coûts complets
- Participation à des projets pilotes
Compétences : Énergie, curiosité transition écologique""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 24
    },
    {
        "offer_title": "Analyste Données Industrielles",
        "offer_desc": """Monitoring des sites de production :
- Développement de jumeaux numériques
- Détection d'anomalies en temps réel
- Optimisation énergétique
- Visualisation des indicateurs clés
Outils : IoT, Python, Tableau""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 24
    },

    # Thales (ID 25) - 3 offres  
    {
        "offer_title": "Stage Systèmes Satellite",
        "offer_desc": """Conception de payloads de communication :
- Simulation des interférences électromagnétiques
- Tests de résistance aux radiations
- Optimisation des architectures RF
- Documentation technique
Profil : Télécom spatiales, MATLAB, anglais technique""",
        "offer_salary": 1850.00,
        "offer_start": datetime.now() + timedelta(days=15),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 25
    },
    {
        "offer_title": "Ingénieur Cryptographie Quantique",
        "offer_desc": """Sécurité post-quantique :
- Évaluation d'algorithmes résistants
- Prototypage de solutions QKD
- Benchmark des technologies émergentes
- Rédaction de brevets
Compétences : Cryptographie, physique quantique""",
        "offer_salary": 1900.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 25
    },
    {
        "offer_title": "Data Scientist Défense",
        "offer_desc": """Traitement de signaux radar :
- Développement de filtres adaptatifs
- Classification automatique de cibles
- Fusion multi-capteurs
- Déploiement sur architectures embarquées
Outils : Python, TensorFlow, C++""",
        "offer_salary": 1800.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=125),
        "company_id": 25
    },

    # Thales (ID 26)
    {
        "offer_title": "Stage Systèmes Embarqués",
        "offer_desc": """Au sein de notre division défense :
- Développer des logiciels temps réel
- Participer à l'intégration matériel/logiciel
- Concevoir des architectures FPGA
- Contribuer à des projets IoT sécurisés
Profil : Électronique/informatique embarquée, C/VHDL, sensibilité cybersécurité.""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now() + timedelta(days=20),
        "offer_end": datetime.now() + timedelta(days=140),
        "company_id": 26
    },
    {
        "offer_title": "Stage Data Science pour la Défense",
        "offer_desc": """Rejoignez notre lab IA :
- Développer des algorithmes de traitement du signal
- Analyser des données radar/satellitaires
- Implémenter des solutions de computer vision
- Optimiser des modèles de reconnaissance
Compétences : Python, TensorFlow/PyTorch, curiosité pour les applications militaires.""",
        "offer_salary": 1800.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 26
    },

    # Pernod Ricard (ID 27) - 3 offres
    {
        "offer_title": "Stage Mixologie Digitale",
        "offer_desc": """Innovation cocktail connecté :
- Développement d'appli de recommandation
- Analyse des tendances consommateurs
- Création de contenus interactifs
- Tests utilisateurs en réalité augmentée
Profil : Double compétence tech/marketing""",
        "offer_salary": 1550.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 27
    },
    {
        "offer_title": "Ingénieur Procédés Distillation",
        "offer_desc": """Optimisation de production :
- Analyse sensorielle des spiritueux
- Modélisation des colonnes à distiller
- Réduction des consommations énergétiques
- Automatisation des contrôles qualité
Compétences : Génie chimique, passion œnologie""",
        "offer_salary": 1650.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 27
    },
    {
        "offer_title": "Analyste Marché Global",
        "offer_desc": """Étude des tendances mondiales :
- Veille concurrentielle internationale
- Modélisation des prévisions de ventes
- Analyse des données douanières
- Recommandations stratégiques
Outils : Power BI, Tableau, Excel avancé""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=125),
        "company_id": 27
    },

    # Kering (ID 28) - 3 offres
    {
        "offer_title": "Stage Luxe Digital",
        "offer_desc": """Expérience client haut de gamme :
- Développement de vitrines virtuelles
- Analyse du parcours client omnicanal
- Conception d'outils CRM exclusifs
- Étude blockchain pour l'authenticité
Profil : Tech avec sens du luxe, créativité""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 28
    },
    {
        "offer_title": "Responsable Production Cuir",
        "offer_desc": """Excellence artisanale :
- Optimisation des découpes matériaux
- Traçabilité des approvisionnements
- Mise en place de normes RSE
- Contrôle qualité expert
Compétences : Savoir-faire artisanal, gestion de production""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=15),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 28
    },
    {
        "offer_title": "Data Scientist Retail Luxe",
        "offer_desc": """Personnalisation de l'offre :
- Analyse des comportements VIP
- Développement de modèles de recommandation
- Pricing algorithmique des collections
- Détection de tendances émergentes
Outils : Python, SQL, Machine Learning""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=125),
        "company_id": 28
    },

    # Dassault Aviation (ID 29) - 3 offres
    {
        "offer_title": "Stage Aérodynamique CFD",
        "offer_desc": """Simulations avancées :
- Maillage complexe d'avions de combat
- Calculs de portance/traînée
- Optimisation des entrées d'air
- Validation sur bancs d'essais
Logiciels : Star-CCM+, ANSYS, CATIA""",
        "offer_salary": 1850.00,
        "offer_start": datetime.now() + timedelta(days=20),
        "offer_end": datetime.now() + timedelta(days=140),
        "company_id": 29
    },
    {
        "offer_title": "Ingénieur Systèmes Avioniques",
        "offer_desc": """Intégration des capteurs :
- Conception d'architectures redondantes
- Tests de compatibilité électromagnétique
- Validation des interfaces pilote-machine
- Documentation technique DO-178C
Profil : Systèmes critiques, rigueur extrême""",
        "offer_salary": 1900.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=120),
        "company_id": 29
    },
    {
        "offer_title": "Stage Matériaux Composites",
        "offer_desc": """Recherche de nouveaux matériaux :
- Essais mécaniques en environnement extrême
- Étude du vieillissement accéléré
- Optimisation des processus de stratification
- Analyse au microscope électronique
Compétences : Matériaux aéro, méthodes de caractérisation""",
        "offer_salary": 1800.00,
        "offer_start": datetime.now() + timedelta(days=10),
        "offer_end": datetime.now() + timedelta(days=130),
        "company_id": 29
    },

    # Hermès (ID 30) - 3 offres
    {
        "offer_title": "Stage Maroquinerie d'Art",
        "offer_desc": """Transmission des savoir-faire :
- Apprentissage des techniques de sellier
- Conception de pièces exceptionnelles
- Contrôle qualité exigeant
- Documentation des processus artisanaux
Profil : Passion métiers d'art, minutie extrême""",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 30
    },
    {
        "offer_title": "Digital Manager Luxe",
        "offer_desc": """Expérience numérique premium :
- Refonte de l'e-commerce haut de gamme
- Analyse des parcours clients internationaux
- Développement de contenus immersifs
- Intégration NFT dans l'univers de la marque
Compétences : UX luxe, technologies émergentes""",
        "offer_salary": 1750.00,
        "offer_start": datetime.now() + timedelta(days=15),
        "offer_end": datetime.now() + timedelta(days=135),
        "company_id": 30
    },
    {
        "offer_title": "Responsable RSE Luxe",
        "offer_desc": """Feuille de route durable :
- Audit de la chaîne d'approvisionnement
- Développement de cuirs éco-responsables
- Calcul de l'empreinte carbone produits
- Stratégie zéro déchet ateliers
Outils : ACV, normes environnementales""",
        "offer_salary": 1700.00,
        "offer_start": datetime.now() + timedelta(days=5),
        "offer_end": datetime.now() + timedelta(days=125),
        "company_id": 30
    }
]

# 🔹 Dictionnaire de mapping offre_id -> [skills_ids]
offer_skills_mapping = {
    # TotalEnergies (ID 1)
    1: [1, 3, 18],      # Ingénieur Énergies Renouvelables – Stage, Python & Énergie renouvelable
    2: [1, 10, 11],     # Analyste Data Énergie – Stage, SQL & Analyse de données

    # AXA (ID 2)
    3: [1, 3, 11],      # Stagiaire Actuariat – Stage, Python & Analyse de données
    4: [1, 42, 23],     # Digital Innovation Assurtech – Stage, UX/UI Design & Communication interpersonnelle

    # Carrefour (ID 3)
    5: [1, 3, 12],      # Stage Data Analyst – Supply Chain, Python & Machine Learning
    6: [1, 15, 11],     # Stagiaire Marketing Digital, Marketing digital & Analyse de données

    # Crédit Agricole (ID 4)
    7: [1, 40, 16],     # Stage Cybersécurité Financière, Cybersécurité & Finance quantitative
    8: [1, 16, 11],     # Analyste Fintech – Stage, Finance quantitative & Analyse de données

    # BNP Paribas (ID 5)
    9: [1, 3, 16],      # Stage Quantitative Finance, Python & Finance quantitative
    10: [1, 11, 16],    # Stagiaire Conformité AML, Analyse de données & Finance quantitative

    # EDF (ID 6)
    11: [1, 32, 24],    # Ingénieur Nucléaire – Stage, Physique appliquée & Travail en équipe
    12: [1, 3, 18],     # Stage Smart Grids, Python & Énergie renouvelable

    # Engie (ID 7)
    13: [1, 18, 27],    # Stage Ingénieur Biométhane, Énergie renouvelable & Adaptabilité
    14: [1, 3, 10],     # Data Analyst Énergie – Stage, Python & SQL

    # Renault (ID 8)
    15: [1, 33, 34],    # Stage Ingénieur Motorisations Électriques, Mécanique & Thermodynamique
    16: [1, 42, 14],    # Stagiaire UX Automotive, UX/UI Design & Conception graphique

    # Stellantis (ID 9)
    17: [1, 33, 25],    # Stage Ingénieur Automobile – Développement de Prototypes, Mécanique & Résolution de problèmes
    18: [1, 33, 11],    # Stage Ingénieur Mécanique – Optimisation de Processus, Mécanique & Analyse de données

    # CMA CGM (ID 10)
    19: [1, 10, 11],    # Stage Analyste Logistique Maritime, SQL & Analyse de données
    20: [1, 22, 26],    # Stage Chef de Projet Logistique, Gestion de projet & Leadership

    # Vinci (ID 11)
    21: [1, 36, 37],    # Stage Ingénieur Construction Durable, Science des matériaux & Ingénierie système
    22: [1, 14, 22],    # Stage Architecte en BTP – Construction Innovante, Conception graphique & Gestion de projet
    23: [1, 36, 11],    # Stage Assistant Bureau d'Études – Structures Écologiques, Science des matériaux & Analyse de données

    # Bouygues (ID 12)
    24: [1, 4, 27],     # Stage Ingénieur Télécoms et Réseaux, Réseaux & Adaptabilité
    25: [1, 40, 27],    # Stage Ingénieur Support Infrastructure IT, Cybersécurité & Adaptabilité

    # LVMH (ID 13)
    26: [1, 15, 23],    # Stage Marketing et Communication Luxe, Marketing digital & Communication interpersonnelle
    27: [1, 15, 22],    # Stage Gestion de Produit – Mode et Luxe, Marketing digital & Gestion de projet

    # Société Générale (ID 14)
    28: [1, 3, 16],     # Stage Finance Quantitative et Analyse des Risques, Python & Finance quantitative
    29: [1, 16, 11],    # Stage Analyste Financier – Modélisation et Stratégie, Finance quantitative & Analyse de données

    # Saint-Gobain (ID 15)
    30: [1, 36, 25],    # Stage R&D Matériaux Innovants, Science des matériaux & Résolution de problèmes
    31: [1, 42, 11],    # Stage Industrie 4.0, UX/UI Design & Analyse de données (pour digitaliser les processus)

    # Veolia (ID 16)
    32: [1, 19, 11],    # Stage Traitement des Eaux, Science environnementale & Analyse de données
    33: [1, 3, 10],     # Stage Data Analyst - Recyclage, Python & SQL

    # Sanofi (ID 17)
    34: [1, 17, 11],    # Stage R&D Pharmaceutique, Santé publique & Analyse de données
    35: [1, 3, 17],     # Stage Data Science Santé, Python & Santé publique
    36: [1, 40, 3],     # Stage Informatique Biomédicale, Cybersécurité & Python

    # Auchan (ID 18)
    37: [1, 3, 10],     # Stage Data Analyst - Retail, Python & SQL
    38: [1, 15, 11],    # Stage Marketing Digital - E-commerce, Marketing digital & Analyse de données
    39: [2, 22, 26],    # Responsable Logistique - CDI, Temps plein, Gestion de projet & Leadership
    40: [2, 22, 3],     # Chef de Projet IT - CDI, Temps plein, Gestion de projet & Python

    # Airbus (ID 19)
    41: [1, 41, 11],    # Stage Ingénierie Aéronautique, Aéronautique & Analyse de données
    42: [1, 40, 41],    # Stagiaire Cybersécurité Avionique, Cybersécurité & Aéronautique
    43: [1, 3, 41],     # Stage Data Science Aéronautique, Python & Aéronautique

    # Orange (ID 20)
    44: [1, 4, 3],      # Ingénieur Réseaux 5G - Stage, Réseaux & Python
    45: [1, 40, 4],     # Stage Cybersécurité Opérateur, Cybersécurité & Réseaux
    46: [1, 11, 3],     # Data Analyst IoT - Stage, Analyse de données & Python

    # Alstom (ID 21)
    47: [1, 37, 25],    # Stage Ingénieur Signalisation Ferroviaire, Ingénierie système & Résolution de problèmes
    48: [1, 36, 25],    # Stage Éco-Conception Trains, Science des matériaux & Résolution de problèmes
    49: [1, 3, 10],     # Analyste Maintenance Prédictive, Python & SQL

    # Danone (ID 22)
    50: [1, 11, 25],    # Stage Nutrition Sensorielle, Analyse de données & Résolution de problèmes
    51: [1, 25, 27],    # Ingénieur Procédés Laitiers, Résolution de problèmes & Adaptabilité
    52: [1, 3, 11],     # Data Analyst Supply Chain, Python & Analyse de données

    # Michelin (ID 23)
    53: [1, 36, 11],    # Stage R&D Matériaux Polymères, Science des matériaux & Analyse de données
    54: [1, 3, 38],     # Stage Industrie 4.0 - Usine Intelligente, Python & Automatisation industrielle

    # Air Liquide (ID 24)
    55: [1, 34, 25],    # Ingénieur Cryogénie - Stage, Thermodynamique & Résolution de problèmes
    56: [1, 18, 11],    # Stage Hydrogène Énergie, Énergie renouvelable & Analyse de données
    57: [1, 3, 11],     # Analyste Données Industrielles, Python & Analyse de données

    # Thales – Bloc 1 (ID 25)
    58: [1, 41, 32],    # Stage Systèmes Satellite, Aéronautique & Physique appliquée
    59: [1, 40, 32],    # Ingénieur Cryptographie Quantique, Cybersécurité & Physique appliquée
    60: [1, 3, 6],      # Data Scientist Défense, Python & C++

    # Thales – Bloc 2 (ID 26)
    61: [1, 40, 35],    # Stage Systèmes Embarqués, Cybersécurité & Électronique
    62: [1, 3, 12],     # Stage Data Science pour la Défense, Python & Machine Learning

    # Pernod Ricard (ID 27)
    63: [1, 15, 42],    # Stage Mixologie Digitale, Marketing digital & UX/UI Design
    64: [1, 34, 25],    # Ingénieur Procédés Distillation, Thermodynamique & Résolution de problèmes
    65: [1, 11, 15],    # Analyste Marché Global, Analyse de données & Marketing digital

    # Kering (ID 28)
    66: [1, 42, 15],    # Stage Luxe Digital, UX/UI Design & Marketing digital
    67: [2, 22, 26],    # Responsable Production Cuir – CDI, Temps plein, Gestion de projet & Leadership
    68: [1, 3, 12],     # Data Scientist Retail Luxe, Python & Machine Learning

    # Dassault Aviation (ID 29)
    69: [1, 41, 33],    # Stage Aérodynamique CFD, Aéronautique & Mécanique
    70: [1, 41, 37],    # Ingénieur Systèmes Avioniques, Aéronautique & Ingénierie système
    71: [1, 36, 41],    # Stage Matériaux Composites, Science des matériaux & Aéronautique

    # Hermès (ID 30)
    72: [1, 14, 23],    # Stage Maroquinerie d'Art, Conception graphique & Communication interpersonnelle
    73: [2, 42, 15],    # Digital Manager Luxe – CDI, Temps plein, UX/UI Design & Marketing digital
    74: [2, 19, 22]     # Responsable RSE Luxe – CDI, Temps plein, Science environnementale & Gestion de projet
}



# 🔹 Liste des candidatures à insérer
applications = [
    {
        "user_id": 3,
        "offer_id": 1,
        "apply_date": datetime.now(),
        "apply_coverletter": "Je suis très intéressé par ce poste.",
        "apply_cv": "/////",
        "apply_status": "En cours",
        "apply_message": "Bonjour, je suis très intéressé par cette offre et je pense que mes compétences correspondent parfaitement aux exigences du poste. J'ai une expérience significative dans ce domaine et je suis convaincu que je pourrais apporter une réelle valeur ajoutée à votre équipe. Je vous remercie de prendre en considération ma candidature et reste à votre disposition pour un entretien."
    },
    {
        "user_id": 4,
        "offer_id": 2,
        "apply_date": datetime.now(),
        "apply_coverletter": "Je pense être un excellent candidat pour ce rôle.",
        "apply_cv": "/////",
        "apply_status": "En cours",
        "apply_message": "Bonjour, je suis très intéressé par cette offre et je pense que mes compétences correspondent parfaitement aux exigences du poste. Mon parcours académique et professionnel m'a permis de développer les compétences nécessaires pour réussir dans ce rôle. Je serais ravi de discuter de ma candidature plus en détail lors d'un entretien. Merci pour votre temps et votre considération."
    },
    {
        "user_id": 5,
        "offer_id": 3,
        "apply_date": datetime.now(),
        "apply_coverletter": "Je possède les compétences requises pour ce poste.",
        "apply_cv": "/////",
        "apply_status": "En cours",
        "apply_message": "Bonjour, je suis très intéressé par cette offre et je pense que mes compétences correspondent parfaitement aux exigences du poste. J'ai une solide expérience dans ce domaine et je suis motivé à contribuer activement à vos projets. Je reste à votre disposition pour toute information complémentaire ou pour un entretien. Merci de considérer ma candidature."
    },
    {
        "user_id": 7,
        "offer_id": 4,
        "apply_date": datetime.now(),
        "apply_coverletter": "Je suis passionné par ce domaine.",
        "apply_cv": "/////",
        "apply_status": "En cours",
        "apply_message": "Bonjour, je suis très intéressé par cette offre et je pense que mes compétences correspondent parfaitement aux exigences du poste. Ma passion pour ce domaine m'a conduit à acquérir des compétences spécifiques qui, je crois, seront un atout pour votre entreprise. Je serais honoré de pouvoir discuter de ma candidature lors d'un entretien. Merci pour votre attention."
    },
    {
        "user_id": 8,
        "offer_id": 5,
        "apply_date": datetime.now(),
        "apply_coverletter": "Je suis enthousiaste à l'idée de cette opportunité.",
        "apply_cv": "/////",
        "apply_status": "En cours",
        "apply_message": "Bonjour, je suis très intéressé par cette offre et je pense que mes compétences correspondent parfaitement aux exigences du poste. Je suis particulièrement attiré par les valeurs de votre entreprise et les missions proposées. Je serais ravi de contribuer à vos projets et de partager mes idées. Merci de considérer ma candidature et je reste disponible pour un entretien."
    }
]

# 🔹 Liste des wishlists à insérer
wishlists = [
    {"user_id": 14, "offer_id": 5, "wishlist_date": datetime.now()},
    {"user_id": 14, "offer_id": 12, "wishlist_date": datetime.now()},
    {"user_id": 14, "offer_id": 20, "wishlist_date": datetime.now()},
    {"user_id": 15, "offer_id": 7, "wishlist_date": datetime.now()},
    {"user_id": 15, "offer_id": 15, "wishlist_date": datetime.now()},
    {"user_id": 15, "offer_id": 25, "wishlist_date": datetime.now()},
    {"user_id": 16, "offer_id": 9, "wishlist_date": datetime.now()},
    {"user_id": 16, "offer_id": 18, "wishlist_date": datetime.now()},
    {"user_id": 16, "offer_id": 30, "wishlist_date": datetime.now()},
    {"user_id": 17, "offer_id": 11, "wishlist_date": datetime.now()},
    {"user_id": 17, "offer_id": 22, "wishlist_date": datetime.now()},
    {"user_id": 17, "offer_id": 35, "wishlist_date": datetime.now()},
    {"user_id": 18, "offer_id": 14, "wishlist_date": datetime.now()},
    {"user_id": 18, "offer_id": 28, "wishlist_date": datetime.now()},
    {"user_id": 18, "offer_id": 40, "wishlist_date": datetime.now()},
    {"user_id": 19, "offer_id": 16, "wishlist_date": datetime.now()},
    {"user_id": 19, "offer_id": 26, "wishlist_date": datetime.now()},
    {"user_id": 19, "offer_id": 38, "wishlist_date": datetime.now()},
    {"user_id": 20, "offer_id": 19, "wishlist_date": datetime.now()},
    {"user_id": 20, "offer_id": 31, "wishlist_date": datetime.now()},
    {"user_id": 20, "offer_id": 50, "wishlist_date": datetime.now()},
    {"user_id": 21, "offer_id": 23, "wishlist_date": datetime.now()},
    {"user_id": 21, "offer_id": 36, "wishlist_date": datetime.now()},
    {"user_id": 21, "offer_id": 48, "wishlist_date": datetime.now()},
    {"user_id": 22, "offer_id": 27, "wishlist_date": datetime.now()},
    {"user_id": 22, "offer_id": 41, "wishlist_date": datetime.now()},
    {"user_id": 22, "offer_id": 60, "wishlist_date": datetime.now()},
    {"user_id": 23, "offer_id": 29, "wishlist_date": datetime.now()},
    {"user_id": 23, "offer_id": 43, "wishlist_date": datetime.now()},
    {"user_id": 23, "offer_id": 55, "wishlist_date": datetime.now()},
    {"user_id": 24, "offer_id": 32, "wishlist_date": datetime.now()},
    {"user_id": 24, "offer_id": 46, "wishlist_date": datetime.now()},
    {"user_id": 24, "offer_id": 61, "wishlist_date": datetime.now()},
    {"user_id": 25, "offer_id": 34, "wishlist_date": datetime.now()},
    {"user_id": 25, "offer_id": 49, "wishlist_date": datetime.now()},
    {"user_id": 25, "offer_id": 63, "wishlist_date": datetime.now()},
    {"user_id": 26, "offer_id": 37, "wishlist_date": datetime.now()},
    {"user_id": 26, "offer_id": 52, "wishlist_date": datetime.now()},
    {"user_id": 26, "offer_id": 64, "wishlist_date": datetime.now()},
    {"user_id": 27, "offer_id": 40, "wishlist_date": datetime.now()},
    {"user_id": 27, "offer_id": 53, "wishlist_date": datetime.now()},
    {"user_id": 27, "offer_id": 66, "wishlist_date": datetime.now()},
    {"user_id": 28, "offer_id": 44, "wishlist_date": datetime.now()},
    {"user_id": 28, "offer_id": 57, "wishlist_date": datetime.now()},
    {"user_id": 28, "offer_id": 68, "wishlist_date": datetime.now()},
    {"user_id": 29, "offer_id": 47, "wishlist_date": datetime.now()},
    {"user_id": 29, "offer_id": 59, "wishlist_date": datetime.now()},
    {"user_id": 29, "offer_id": 70, "wishlist_date": datetime.now()},
    {"user_id": 30, "offer_id": 51, "wishlist_date": datetime.now()},
    {"user_id": 30, "offer_id": 62, "wishlist_date": datetime.now()},
    {"user_id": 30, "offer_id": 71, "wishlist_date": datetime.now()}
]

# 🔹 Liste des evaluations à insérer
evaluations = [
    {
        "user_id": 1,
        "company_id": 1,
        "feedback_rate": 5,
        "feedback_comment": "Excellente entreprise avec de grandes opportunités."
    },
    {
        "user_id": 2,
        "company_id": 2,
        "feedback_rate": 4,
        "feedback_comment": "Bonne expérience, mais des améliorations sont nécessaires."
    },
    {
        "user_id": 3,
        "company_id": 3,
        "feedback_rate": 5,
        "feedback_comment": "Environnement de travail incroyable et équipe solidaire."
    },
    {
        "user_id": 4,
        "company_id": 4,
        "feedback_rate": 3,
        "feedback_comment": "Expérience moyenne, pourrait être meilleure."
    },
    {
        "user_id": 5,
        "company_id": 5,
        "feedback_rate": 4,
        "feedback_comment": "Excellente expérience d'apprentissage avec des mentors compétents."
    },
    {
        "user_id": 6,
        "company_id": 6,
        "feedback_rate": 5,
        "feedback_comment": "Entreprise innovante avec des projets passionnants."
    },
    {
        "user_id": 7,
        "company_id": 7,
        "feedback_rate": 4,
        "feedback_comment": "Bonne ambiance de travail, mais quelques défis organisationnels."
    },
    {
        "user_id": 8,
        "company_id": 8,
        "feedback_rate": 5,
        "feedback_comment": "Une expérience enrichissante avec des opportunités de croissance."
    },
    {
        "user_id": 9,
        "company_id": 9,
        "feedback_rate": 3,
        "feedback_comment": "Des processus à améliorer pour une meilleure efficacité."
    },
    {
        "user_id": 10,
        "company_id": 10,
        "feedback_rate": 4,
        "feedback_comment": "Une équipe dynamique et des projets intéressants."
    }
]

# 🔹 Liste des skills à insérer
skills = [
    {"skills_name": "Stage"},
    {"skills_name": "Temps plein"},
    {"skills_name": "Python"},
    {"skills_name": "Réseaux"},
    {"skills_name": "Microsoft 365"},
    {"skills_name": "C++"},
    {"skills_name": "Java"},
    {"skills_name": "HTML/CSS"},
    {"skills_name": "JavaScript"},
    {"skills_name": "SQL"},
    {"skills_name": "Analyse de données"},
    {"skills_name": "Machine Learning"},
    {"skills_name": "Développement web"},
    {"skills_name": "Conception graphique"},
    {"skills_name": "Marketing digital"},
    {"skills_name": "Finance quantitative"},
    {"skills_name": "Santé publique"},
    {"skills_name": "Énergie renouvelable"},
    {"skills_name": "Science environnementale"},
    {"skills_name": "E-learning"},
    {"skills_name": "Banque d'investissement"},
    {"skills_name": "Gestion de projet"},
    {"skills_name": "Communication interpersonnelle"},
    {"skills_name": "Travail en équipe"},
    {"skills_name": "Résolution de problèmes"},
    {"skills_name": "Leadership"},
    {"skills_name": "Adaptabilité"},
    {"skills_name": "Gestion du temps"},
    {"skills_name": "Esprit critique"},
    {"skills_name": "Prise de décision"},
    {"skills_name": "Négociation"},
    {"skills_name": "Physique appliquée"},
    {"skills_name": "Mécanique"},
    {"skills_name": "Thermodynamique"},
    {"skills_name": "Électronique"},
    {"skills_name": "Science des matériaux"},
    {"skills_name": "Ingénierie système"},
    {"skills_name": "Automatisation industrielle"},
    {"skills_name": "Robotique"},
    {"skills_name": "Cybersécurité"},  # Nouvelle compétence
    {"skills_name": "Aéronautique"},    # Nouvelle compétence
    {"skills_name": "UX/UI Design"}     # Nouvelle compétence
]

# 🔹 Liste des détails à insérer
details = [
    {"skills_id": 1, "detail": "Stage: Une période de formation pratique en entreprise."},
    {"skills_id": 2, "detail": "Temps plein: Un emploi à temps plein avec des heures de travail régulières."},
    {"skills_id": 3, "detail": "Python: Un langage de programmation polyvalent et populaire."},
    {"skills_id": 4, "detail": "Réseau: Compétences en gestion et configuration des réseaux informatiques."},
    {"skills_id": 5, "detail": "Office 360: Maîtrise des outils Microsoft Office 365."},
    {"skills_id": 6, "detail": "C++: Un langage de programmation utilisé pour le développement de logiciels."},
    {"skills_id": 7, "detail": "Java: Un langage de programmation utilisé pour le développement d'applications."},
    {"skills_id": 8, "detail": "HTML/CSS: Langages de base pour la création de pages web."},
    {"skills_id": 9, "detail": "JavaScript: Un langage de programmation pour le développement web interactif."},
    {"skills_id": 10, "detail": "SQL: Langage de requête pour la gestion des bases de données."},
    {"skills_id": 11, "detail": "Analyse de données: Compétences en analyse et interprétation des données."},
    {"skills_id": 12, "detail": "Machine Learning: Techniques d'apprentissage automatique pour l'analyse des données."},
    {"skills_id": 13, "detail": "Développement web: Compétences en création et maintenance de sites web."},
    {"skills_id": 14, "detail": "Conception graphique: Compétences en création de visuels et de designs."},
    {"skills_id": 15, "detail": "Marketing: Compétences en promotion et vente de produits ou services."},
    {"skills_id": 16, "detail": "Finance: Compétences en gestion financière et analyse économique."},
    {"skills_id": 17, "detail": "Soins de santé: Compétences en services médicaux et soins aux patients."},
    {"skills_id": 18, "detail": "Énergie renouvelable: Compétences en technologies d'énergie verte."},
    {"skills_id": 19, "detail": "Science de l'environnement: Compétences en étude et protection de l'environnement."},
    {"skills_id": 20, "detail": "E-learning: Compétences en développement de plateformes d'apprentissage en ligne."},
    {"skills_id": 21, "detail": "Banque d'investissement: Compétences en services financiers et investissements."},
    {"skills_id": 22, "detail": "Gestion de projet: Compétences en planification et exécution de projets."},
    {"skills_id": 23, "detail": "Communication: Compétences en transmission d'informations et relations interpersonnelles."},
    {"skills_id": 24, "detail": "Travail en équipe: Compétences en collaboration et travail collectif."},
    {"skills_id": 25, "detail": "Résolution de problèmes: Compétences en identification et résolution de problèmes."},
    {"skills_id": 26, "detail": "Leadership: Compétences en gestion et motivation d'équipe."},
    {"skills_id": 27, "detail": "Adaptabilité: Capacité à s'adapter à des situations nouvelles et changeantes."},
    {"skills_id": 28, "detail": "Gestion du temps: Compétences en organisation et gestion efficace du temps."},
    {"skills_id": 29, "detail": "Esprit critique: Capacité à analyser et évaluer des informations de manière objective."},
    {"skills_id": 30, "detail": "Prise de décision: Compétences en prise de décisions éclairées et efficaces."},
    {"skills_id": 31, "detail": "Négociation: Compétences en négociation et gestion des conflits."},
    {"skills_id": 32, "detail": "Physique: Compétences en sciences physiques et applications pratiques."},
    {"skills_id": 33, "detail": "Mécanique: Compétences en ingénierie mécanique et conception de machines."},
    {"skills_id": 34, "detail": "Thermodynamique: Compétences en étude des échanges thermiques et énergétiques."},
    {"skills_id": 35, "detail": "Électronique: Compétences en conception et maintenance de circuits électroniques."},
    {"skills_id": 36, "detail": "Matériaux: Compétences en science des matériaux et leurs applications."},
    {"skills_id": 37, "detail": "Ingénierie des systèmes: Compétences en conception et gestion de systèmes complexes."},
    {"skills_id": 38, "detail": "Automatisation: Compétences en automatisation des processus industriels."},
    {"skills_id": 39, "detail": "Robotique: Compétences en conception et programmation de robots."}
]

# 🔹 Fonction pour se connecter à MySQL
def connect_db():
    return mysql.connector.connect(
        host="localhost",
        user="Internity",
        password="In/TernityXX25!",
        database="Internity"
    )

# 🔹 Fonction pour créer toutes les tables
def create_tables(cursor):
    tables = [
        """
        CREATE TABLE IF NOT EXISTS Users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            user_surname VARCHAR(50),
            user_email VARCHAR(50) NOT NULL UNIQUE,
            user_name VARCHAR(50),
            user_password VARCHAR(255) NOT NULL,
            user_coverletter VARCHAR(255),
            user_cv VARCHAR(255)
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Companies (
            company_id INT AUTO_INCREMENT PRIMARY KEY,
            company_name VARCHAR(100) NOT NULL,
            company_desc VARCHAR(255),
            company_business VARCHAR(50),
            company_email VARCHAR(100) NOT NULL UNIQUE,
            company_phone VARCHAR(20),
            company_averagerate DECIMAL(10,2) DEFAULT 0.00,
            company_address VARCHAR(100)
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Regions (
            region_id INT AUTO_INCREMENT PRIMARY KEY,
            region_name VARCHAR(50) NOT NULL UNIQUE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Cities (
            city_id INT AUTO_INCREMENT PRIMARY KEY,
            city_name VARCHAR(50) NOT NULL UNIQUE,
            city_code INT NOT NULL,
            region_id INT NOT NULL,
            FOREIGN KEY(region_id) REFERENCES Regions(region_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Pilotes (
            pilote_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL UNIQUE,
            FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Admins (
            admin_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL UNIQUE,
            FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Skills (
            skills_id INT AUTO_INCREMENT PRIMARY KEY,
            skills_name VARCHAR(50) NOT NULL UNIQUE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Offers (
            offer_id INT AUTO_INCREMENT PRIMARY KEY,
            offer_title VARCHAR(100) NOT NULL,
            offer_desc VARCHAR(2000),
            offer_salary DECIMAL(10,2) DEFAULT 0.00,
            offer_start DATETIME NOT NULL,
            offer_end DATETIME NOT NULL,
            offer_countapply INT DEFAULT 0,
            company_id INT NOT NULL,
            FOREIGN KEY(company_id) REFERENCES Companies(company_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Promotions (
            promotion_id INT AUTO_INCREMENT PRIMARY KEY,
            promotion_name VARCHAR(50) NOT NULL,
            promotion_desc VARCHAR(255),
            pilote_id INT NOT NULL,
            FOREIGN KEY(pilote_id) REFERENCES Pilotes(pilote_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Students (
            student_id INT AUTO_INCREMENT PRIMARY KEY,
            promotion_id INT NOT NULL,
            user_id INT NOT NULL UNIQUE,
            FOREIGN KEY(promotion_id) REFERENCES Promotions(promotion_id) ON DELETE CASCADE,
            FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Wishlists (
            user_id INT NOT NULL,
            offer_id INT NOT NULL,
            wishlist_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(user_id, offer_id),
            FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
            FOREIGN KEY(offer_id) REFERENCES Offers(offer_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Applications (
            user_id INT NOT NULL,
            offer_id INT NOT NULL,
            apply_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            apply_coverletter VARCHAR(255),
            apply_cv VARCHAR(255),
            apply_message VARCHAR(255),
            apply_status VARCHAR(50) DEFAULT 'En attente',
            PRIMARY KEY(user_id, offer_id),
            FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
            FOREIGN KEY(offer_id) REFERENCES Offers(offer_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Evaluations (
            user_id INT NOT NULL,
            company_id INT NOT NULL,
            feedback_rate INT CHECK (feedback_rate BETWEEN 1 AND 5),
            feedback_comment VARCHAR(255),
            PRIMARY KEY(user_id, company_id),
            FOREIGN KEY(user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
            FOREIGN KEY(company_id) REFERENCES Companies(company_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Located (
            company_id INT NOT NULL,
            city_id INT NOT NULL,
            launch_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(company_id, city_id),
            FOREIGN KEY(company_id) REFERENCES Companies(company_id) ON DELETE CASCADE,
            FOREIGN KEY(city_id) REFERENCES Cities(city_id) ON DELETE CASCADE
        );
        """,
        """
        CREATE TABLE IF NOT EXISTS Details (
            offer_id INT NOT NULL,
            skills_id INT NOT NULL,
            detail VARCHAR(255),
            PRIMARY KEY(offer_id, skills_id),
            FOREIGN KEY(offer_id) REFERENCES Offers(offer_id) ON DELETE CASCADE,
            FOREIGN KEY(skills_id) REFERENCES Skills(skills_id) ON DELETE CASCADE
        );
        """
    ]

    for query in tables:
        cursor.execute(query)

# 🔹 Fonction pour insérer les régions
def insert_regions(cursor, conn, data):
    regions_extraites = set(city["region_geojson_name"] for city in data["cities"])
    regions_filtrees = [(region,) for region in regions_extraites if region in regions_officielles]

    if regions_filtrees:
        cursor.executemany("INSERT IGNORE INTO Regions (region_name) VALUES (%s)", regions_filtrees)
        conn.commit()
        print(f"✅ {cursor.rowcount} régions insérées dans Regions.")
    else:
        print("⚠️ Aucune région valide trouvée.")

# 🔹 Fonction pour insérer les villes
def insert_cities(cursor, conn, data):
    cursor.execute("SELECT region_id, region_name FROM Regions")
    region_map = {name: rid for rid, name in cursor.fetchall()}

    cities = [
        (city["label"].capitalize(), city["zip_code"], region_map.get(city["region_geojson_name"], None))
        for city in data["cities"] if city["region_geojson_name"] in region_map
    ]

    if cities:
        cursor.executemany("INSERT IGNORE INTO Cities (city_name, city_code, region_id) VALUES (%s, %s, %s)", cities)
        conn.commit()
        print(f"✅ {cursor.rowcount} villes insérées dans Cities.")
    else:
        print("⚠️ Aucune ville insérée.")

# 🔹 Fonction pour insérer les entreprises
def insert_companies(cursor, con, companies):
    if companies:
        cursor.executemany("INSERT IGNORE INTO Companies (company_name, company_desc, company_business, company_email, company_phone, company_averagerate, company_address) VALUES (%s, %s, %s, %s, %s, %s, %s)", companies)
        con.commit()
        print(f"✅ {cursor.rowcount} entreprises insérées dans Companies.")
    else:
        print("⚠️ Aucune entreprise insérée.")

# 🔹 Fonction pour insérer les utilisateurs
def insert_users(cursor, conn, users):
    if users:
        cursor.executemany("INSERT IGNORE INTO Users (user_surname, user_email, user_name, user_password, user_coverletter, user_cv) VALUES (%s, %s, %s, %s, %s, %s)", users)
        conn.commit()
        print(f"✅ {cursor.rowcount} utilisateurs insérés dans Users.")
    else:
        print("⚠️ Aucun utilisateur")

# 🔹 Fonction pour insérer les admins
def insert_admins(cursor, conn, users):
    # Désactiver les contraintes de clé étrangère pour pouvoir troncature la table
    cursor.execute("SET foreign_key_checks = 0;")
    
    # Réinitialiser l'auto-incrément de la table Admins
    cursor.execute("TRUNCATE TABLE Admins")
    
    # Réactiver les contraintes de clé étrangère
    cursor.execute("SET foreign_key_checks = 1;")
    
    # 🔹 Insérer les 3 premiers utilisateurs comme Admins
    inserted_count = 0
    for user in users[:3]:
        cursor.execute("INSERT INTO Admins (user_id) SELECT user_id FROM Users WHERE user_email = %s", (user[1],))
        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées
    
    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} admins insérés dans Admins.")

# 🔹 Fonction pour insérer les pilotes
def insert_pilotes(cursor, conn, users):
    # Désactiver les contraintes de clé étrangère pour pouvoir troncature la table
    cursor.execute("SET foreign_key_checks = 0;")
    
    # Réinitialiser l'auto-incrément de la table Pilotes
    cursor.execute("TRUNCATE TABLE Pilotes")
    
    # Réactiver les contraintes de clé étrangère
    cursor.execute("SET foreign_key_checks = 1;")
    
    # 🔹 Insérer les 10 suivants utilisateurs comme Pilotes
    inserted_count = 0
    for user in users[3:13]:
        cursor.execute("INSERT INTO Pilotes (user_id) SELECT user_id FROM Users WHERE user_email = %s", (user[1],))
        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées
    
    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} pilotes insérés dans Pilotes.")

# 🔹 Fonction pour récupérer le nombre de pilotes dans la table Pilotes
def get_number_of_pilotes(cursor):
    cursor.execute("SELECT COUNT(*) FROM Pilotes")
    result = cursor.fetchone()
    return result[0]  # Le nombre de pilotes

# 🔹 Fonction pour insérer les promotions avec un pilote aléatoire
def insert_promotions(cursor, conn, promotions):
    # Vérifier si la table Promotions est vide
    cursor.execute("SELECT COUNT(*) FROM Promotions")
    count = cursor.fetchone()[0]
    
    # Si la table est vide, réinitialiser l'auto-incrémentation
    if count == 0:
        cursor.execute("ALTER TABLE Promotions AUTO_INCREMENT = 1")
    
    # Récupérer le nombre de pilotes existants dans la table Pilotes
    cursor.execute("SELECT COUNT(*) FROM Pilotes")
    number_of_pilotes = cursor.fetchone()[0]

    inserted_count = 0
    for promotion in promotions:
        # Sélectionner un pilote_id aléatoire entre 1 et le nombre de pilotes
        pilote_id = random.randint(1, number_of_pilotes)
        
        cursor.execute(
            """
            INSERT INTO Promotions (promotion_name, promotion_desc, pilote_id)
            VALUES (%s, %s, %s)
            """, 
            (promotion["promotion_name"], promotion["promotion_desc"], pilote_id)
        )
        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées
    
    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} promotions insérées dans Promotions.")

# 🔹 Fonction pour insérer les étudiants avec une promotion aléatoire
def insert_students(cursor, conn):
    # Compter le nombre de promotions existantes
    cursor.execute("SELECT promotion_id FROM Promotions")
    promotions = cursor.fetchall()  # Récupérer tous les promotion_id existants
    number_of_promotions = len(promotions)

    if number_of_promotions == 0:
        print("❌ Aucune promotion disponible !")
        return

    # Vérifier si la table Students est vide
    cursor.execute("SELECT COUNT(*) FROM Students")
    count = cursor.fetchone()[0]
    
    if count > 0:
        print("✅ 0 students insérés dans Students.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Students AUTO_INCREMENT = 1")
    print("🔄 Auto-incrémentation réinitialisée à 1.")

    cursor.execute("SELECT user_id FROM Users ORDER BY user_id ASC")
    user_ids = cursor.fetchall()

    # Nombre d'utilisateurs à partir du 14e
    number_of_users = len(user_ids) - 14  # Début à l'index 13 (14e utilisateur)

    # 🔹 Afficher le nombre d'utilisateurs
    print(f"Nombre d'utilisateurs à insérer : {number_of_users}")

    if number_of_users == 0:
        print("❌ Aucun utilisateur disponible à partir du 14e.")
    else:
        # 🔹 Insérer les étudiants
        inserted_count = 0
        for i in range(13, len(user_ids)):  # À partir de l'index 13 jusqu'à la fin
            user_id = user_ids[i][0]  # user_id est dans la première position du tuple
            # Sélectionner une promotion aléatoire
            promotion_id = random.randint(1, 5)  # Sélectionner un promotion_id entre 1 et 5
            
            cursor.execute(
                """
                INSERT INTO Students (promotion_id, user_id)
                VALUES (%s, %s)
                """, 
                (promotion_id, user_id)
            )
            inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

        conn.commit()  # Valider les changements dans la base de données
        print(f"✅ {inserted_count} étudiants insérés dans Students.")

# 🔹 Fonction pour insérer les compétences avec une vérification
def insert_skills(cursor, conn, skills):
    # Vérifier si la table Skills est vide
    cursor.execute("SELECT COUNT(*) FROM Skills")
    count = cursor.fetchone()[0]
    
    if count > 0:
        print("✅ 0 compétences insérées dans Skills.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Skills AUTO_INCREMENT = 1")

    # Insérer les compétences dans la table Skills
    inserted_count = 0
    for skill in skills:
        cursor.execute(
            """
            INSERT INTO Skills (skills_name)
            VALUES (%s)
            """, 
            (skill["skills_name"],)
        )
        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} compétences insérées dans Skills.")

# 🔹 Fonction pour insérer les offres de stage
def insert_offers(cursor, conn, offers):
    # Vérifier si la table Offers est vide
    cursor.execute("SELECT COUNT(*) FROM Offers")
    count = cursor.fetchone()[0]
    
    if count > 0:
        print("✅ 0 offres insérées dans Offers.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Offers AUTO_INCREMENT = 1")

    # Récupérer le nombre d'entreprises existantes dans la table Companies
    cursor.execute("SELECT COUNT(*) FROM Companies")
    number_of_companies = cursor.fetchone()[0]

    if number_of_companies == 0:
        print("❌ Aucune entreprise disponible !")
        return

    # Insérer les offres de stage dans la table Offers
    inserted_count = 0
    for offer in offers:
        # Sélectionner un company_id aléatoire entre 1 et le nombre d'entreprises
        company_id = random.randint(1, number_of_companies)
        
        cursor.execute(
            """
            INSERT INTO Offers (offer_title, offer_desc, offer_salary, offer_start, offer_end, company_id)
            VALUES (%s, %s, %s, %s, %s, %s)
            """, 
            (offer["offer_title"], offer["offer_desc"], offer["offer_salary"], offer["offer_start"], offer["offer_end"], company_id)
        )

        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} offres insérées dans Offers.")

# 🔹 Fonction pour insérer les candidatures
def insert_applications(cursor, conn, applications):
    # Vérifier si la table Applications est vide
    cursor.execute("SELECT COUNT(*) FROM Applications")
    count = cursor.fetchone()[0]
    
    if count > 0:
        print("✅ 0 candidatures insérées dans Applications.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Applications AUTO_INCREMENT = 1")

    # Récupérer le nombre de students et d'offres existants
    cursor.execute("SELECT COUNT(*) FROM Students")
    number_of_students = cursor.fetchone()[0]

    cursor.execute("SELECT COUNT(*) FROM Offers")
    number_of_offers = cursor.fetchone()[0]

    if number_of_students == 0 or number_of_offers == 0:
        print("❌ Aucun étudiant ou aucune offre disponible !")
        return

    # Insérer les candidatures dans la table Applications
    inserted_count = 0
    for application in applications:
        # Sélectionner un user_id et un offer_id aléatoires
        user_id = random.randint(1, number_of_students)
        offer_id = random.randint(1, number_of_offers)
        
        cursor.execute(
            """
            INSERT INTO Applications (user_id, offer_id, apply_date, apply_coverletter, apply_cv)
            VALUES (%s, %s, %s, %s, %s)
            """, 
            (user_id, offer_id, application["apply_date"], application["apply_coverletter"], application["apply_cv"])
        )

        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} candidatures insérées dans Applications.")

# 🔹 Fonction pour insérer les favoris
def insert_wishlists(cursor, conn, wishlists):
    # Vérifier si la table Wishlists est vide
    cursor.execute("SELECT COUNT(*) FROM Wishlists")
    count = cursor.fetchone()[0]
    
    if count > 0:
        print("✅ 0 favoris insérés dans Wishlists.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Wishlists AUTO_INCREMENT = 1")

    # Récupérer le nombre d'utilisateurs et d'offres existants
    cursor.execute("SELECT COUNT(*) FROM Users")
    number_of_users = cursor.fetchone()[0]

    cursor.execute("SELECT COUNT(*) FROM Offers")
    number_of_offers = cursor.fetchone()[0]

    if number_of_users == 0 or number_of_offers == 0:
        print("❌ Aucun utilisateur ou aucune offre disponible !")
        return

    # Insérer les favoris dans la table Wishlists
    inserted_count = 0
    for wishlist in wishlists:
        # Sélectionner un user_id et un offer_id aléatoires
        user_id = random.randint(1, number_of_users)
        offer_id = random.randint(1, number_of_offers)
        
        cursor.execute(
            """
            INSERT INTO Wishlists (user_id, offer_id, wishlist_date)
            VALUES (%s, %s, %s)
            """, 
            (user_id, offer_id, wishlist["wishlist_date"])
        )

        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} favoris insérés dans Wishlists.")

# 🔹 Fonction pour insérer les évaluations
def insert_evaluations(cursor, conn, evaluations):
    # Vérifier si la table Evaluations est vide
    cursor.execute("SELECT COUNT(*) FROM Evaluations")
    count = cursor.fetchone()[0]
    
    if count > 0:
        print("✅ 0 évaluations insérées dans Evaluations.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Evaluations AUTO_INCREMENT = 1")

    # Récupérer le nombre d'utilisateurs et d'entreprises existants
    cursor.execute("SELECT COUNT(*) FROM Users")
    number_of_users = cursor.fetchone()[0]

    cursor.execute("SELECT COUNT(*) FROM Companies")
    number_of_companies = cursor.fetchone()[0]

    if number_of_users == 0 or number_of_companies == 0:
        print("❌ Aucun utilisateur ou aucune entreprise disponible !")
        return

    # Insérer les évaluations dans la table Evaluations
    inserted_count = 0
    for evaluation in evaluations:
        # Sélectionner un user_id et un company_id aléatoires
        user_id = random.randint(1, number_of_users)
        company_id = random.randint(1, number_of_companies)
        
        cursor.execute(
            """
            INSERT INTO Evaluations (user_id, company_id, feedback_rate, feedback_comment)
            VALUES (%s, %s, %s, %s)
            """, 
            (user_id, company_id, evaluation["feedback_rate"], evaluation["feedback_comment"])
        )

        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} évaluations insérées dans Evaluations.")

# 🔹 Fonction pour insérer les détails
def insert_details(cursor, conn, details):
    # Vérifier l'existence des données
    cursor.execute("SELECT COUNT(*) FROM Details")
    if cursor.fetchone()[0] > 0:
        print("✅ 0 détails insérés (déjà existants)")
        return

    # Création des détails basés sur le mapping
    inserted_count = 0
    for offer_idx, offer in enumerate(offers):
        offer_id = offer_idx + 1  # Les IDs commencent à 1
        skills_ids = offer_skills_mapping.get(offer_id, [])
        
        if not skills_ids:
            print(f"⚠️ Aucune compétence trouvée pour l'offre {offer_id}")
            continue
        
        for skill_id in skills_ids:
            # Validation de l'existence de la compétence
            cursor.execute("SELECT skills_name FROM Skills WHERE skills_id = %s", (skill_id,))
            if not cursor.fetchone():
                print(f"❌ Compétence {skill_id} inexistante pour l'offre {offer_id}")
                continue
            
            # Création du détail contextualisé
            detail_text = f"{offer['offer_title']} - {skills[skill_id-1]['skills_name']}"
            cursor.execute(
                """
                INSERT INTO Details (offer_id, skills_id, detail)
                VALUES (%s, %s, %s)
                """,
                (offer_id, skill_id, detail_text)
            )
            inserted_count += cursor.rowcount
    
    conn.commit()
    print(f"✅ {inserted_count} associations skills/offres insérées avec contexte métier")

# 🔹 Fonction pour insérer la table Located
def insert_located(cursor, conn):
    # Vérifier si la table Located est vide
    cursor.execute("SELECT COUNT(*) FROM Located")
    count = cursor.fetchone()[0]
    
    if count > 0:
        print("✅ 0 localisations insérées dans Located.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Located AUTO_INCREMENT = 1")

    # Insérer les localisations dans la table Located
    inserted_count = 0
    for company_id in range(1, 31):  # Supposons qu'il y a 30 entreprises
        city_id = random.randint(1, 32000)  # Générer un city_id aléatoire
        random_days = random.randint(0, 5000)
        random_date = datetime.now() - timedelta(days=random_days)

        cursor.execute(
            """
            INSERT INTO Located (company_id, city_id, launch_date)
            VALUES (%s, %s, %s)
            """, 
            (company_id, city_id, random_date)
        )
        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} localisations insérées dans Located.")


# 🔹 Script principal
try:
    conn = connect_db()
    cursor = conn.cursor()

    create_tables(cursor)
    conn.commit()
    print("✅ Tables créées avec succès.")

    insert_regions(cursor, conn, data)
    insert_cities(cursor, conn, data)
    insert_companies(cursor, conn, companies)
    insert_users(cursor, conn, users)
    insert_admins(cursor, conn, users)
    insert_pilotes(cursor, conn, users)
    insert_promotions(cursor, conn, promotions)
    insert_students(cursor, conn)
    insert_skills(cursor, conn, skills)
    insert_offers(cursor, conn, offers)
    insert_applications(cursor, conn, applications)
    insert_wishlists(cursor, conn, wishlists)
    insert_evaluations(cursor, conn, evaluations)
    insert_details(cursor, conn, details)
    insert_located(cursor, conn)

# except mysql.connector.Error as err:
#     print(f"❌ Erreur MySQL : {err}")
except AttributeError: pass

finally:
    cursor.close()
    conn.close()
    print("🔌 Connexion fermée.")
