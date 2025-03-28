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

# # Transformer le hash
# for user in users:
#     user = list(user)  # Convert tuple to list to allow modification
#     user[3] = hashlib.sha512(user[1].encode()).hexdigest()
#     print(f"('{user[0]}', '{user[1]}', '{user[2]}', '{user[3]}', '{user[4]}', '{user[5]}'),")

# # Vérifier le hash
# for user in users:
#     if user[3] == hashlib.sha512(user[1].encode()).hexdigest():
#         #print(f"Hash correct : {user[3]}")
#         pass
#     else:
#         print(f"Hash incorrect : {user[3]}")


# 🔹 Liste des promotions à insérer
promotions = [
    {
        "promotion_name" : "CPI A1",
        "promotion_desc" : "Promotion CPI A1 - 1ère Année"
    },
    {
        "promotion_name" : "CPI A2 Info",
        "promotion_desc" : "Promotion CPI A2 - 2ème Année - Spécialité Informatique"
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
    {
        "offer_title": "Software Development Intern",
        "offer_desc": "Work on developing cutting-edge software solutions.",
        "offer_salary": 1500.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 1
    },
    {
        "offer_title": "Data Analyst Intern",
        "offer_desc": "Analyze data to help drive business decisions.",
        "offer_salary": 1400.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 1
    },
    {
        "offer_title": "Renewable Energy Engineer Intern",
        "offer_desc": "Assist in designing and implementing renewable energy solutions.",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 2
    },
    {
        "offer_title": "Environmental Scientist Intern",
        "offer_desc": "Conduct research and analysis on environmental impact.",
        "offer_salary": 1500.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 2
    },
    {
        "offer_title": "Healthcare IT Intern",
        "offer_desc": "Support IT infrastructure in a healthcare setting.",
        "offer_salary": 1550.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 3
    },
    {
        "offer_title": "Medical Research Intern",
        "offer_desc": "Assist in medical research projects.",
        "offer_salary": 1500.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 3
    },
    {
        "offer_title": "Educational Content Developer Intern",
        "offer_desc": "Create and manage educational content.",
        "offer_salary": 1450.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 4
    },
    {
        "offer_title": "E-learning Platform Developer Intern",
        "offer_desc": "Develop and maintain e-learning platforms.",
        "offer_salary": 1500.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 4
    },
    {
        "offer_title": "Financial Analyst Intern",
        "offer_desc": "Analyze financial data and create reports.",
        "offer_salary": 1600.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 5
    },
    {
        "offer_title": "Investment Banking Intern",
        "offer_desc": "Assist in investment banking operations.",
        "offer_salary": 1700.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 5
    },
    {
        "offer_title": "Graphic Design Intern",
        "offer_desc": "Create visual content for marketing campaigns.",
        "offer_salary": 1400.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 6
    },
    {
        "offer_title": "Marketing Intern",
        "offer_desc": "Assist in developing marketing strategies.",
        "offer_salary": 1450.00,
        "offer_start": datetime.now(),
        "offer_end": datetime.now() + timedelta(days=90),
        "company_id": 6
    }
]

# 🔹 Liste des candidatures à insérer
applications = [
    {
        "user_id": 3,
        "offer_id": 1,
        "apply_date": datetime.now(),
        "apply_coverletter": "I am very interested in this position.",
        "apply_cv": "/////"
    },
    {
        "user_id": 4,
        "offer_id": 2,
        "apply_date": datetime.now(),
        "apply_coverletter": "I believe I am a great fit for this role.",
        "apply_cv": "/////"
    },
    {
        "user_id": 5,
        "offer_id": 3,
        "apply_date": datetime.now(),
        "apply_coverletter": "I have the skills required for this position.",
        "apply_cv": "/////"
    },
    {
        "user_id": 7,
        "offer_id": 4,
        "apply_date": datetime.now(),
        "apply_coverletter": "I am passionate about this field.",
        "apply_cv": "/////"
    },
    {
        "user_id": 8,
        "offer_id": 5,
        "apply_date": datetime.now(),
        "apply_coverletter": "I am excited about this opportunity.",
        "apply_cv": "/////"
    }
]

# 🔹 Liste des wishlists à insérer
wishlists = [
    {
        "user_id": 3,
        "offer_id": 1,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 3,
        "offer_id": 2,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 4,
        "offer_id": 2,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 4,
        "offer_id": 3,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 5,
        "offer_id": 3,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 5,
        "offer_id": 4,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 7,
        "offer_id": 4,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 7,
        "offer_id": 5,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 8,
        "offer_id": 5,
        "wishlist_date": datetime.now()
    },
    {
        "user_id": 8,
        "offer_id": 6,
        "wishlist_date": datetime.now()
    }
]

# 🔹 Liste des evaluations à insérer
evaluations = [
    {
        "user_id": 1,
        "company_id": 1,
        "feedback_rate": 5,
        "feedback_comment": "Excellent company with great opportunities."
    },
    {
        "user_id": 2,
        "company_id": 2,
        "feedback_rate": 4,
        "feedback_comment": "Good experience, but some improvements needed."
    },
    {
        "user_id": 3,
        "company_id": 3,
        "feedback_rate": 5,
        "feedback_comment": "Amazing work environment and supportive team."
    },
    {
        "user_id": 4,
        "company_id": 4,
        "feedback_rate": 3,
        "feedback_comment": "Average experience, could be better."
    },
    {
        "user_id": 5,
        "company_id": 5,
        "feedback_rate": 4,
        "feedback_comment": "Great learning experience with knowledgeable mentors."
    }
]

# 🔹 Liste des skills à insérer
skills = [
    {"skills_name": "Stage"},
    {"skills_name": "Temps plein"},
    {"skills_name": "Python"},
    {"skills_name": "Réseau"},
    {"skills_name": "Office 360"},
    {"skills_name": "C++"},
    {"skills_name": "Java"},
    {"skills_name": "HTML/CSS"},
    {"skills_name": "JavaScript"},
    {"skills_name": "SQL"},
    {"skills_name": "Analyse de données"},
    {"skills_name": "Machine Learning"},
    {"skills_name": "Développement web"},
    {"skills_name": "Conception graphique"},
    {"skills_name": "Marketing"},
    {"skills_name": "Finance"},
    {"skills_name": "Soins de santé"},
    {"skills_name": "Énergie renouvelable"},
    {"skills_name": "Science de l'environnement"},
    {"skills_name": "E-learning"},
    {"skills_name": "Banque d'investissement"},
    {"skills_name": "Gestion de projet"},
    {"skills_name": "Communication"},
    {"skills_name": "Travail en équipe"},
    {"skills_name": "Résolution de problèmes"},
    {"skills_name": "Leadership"},
    {"skills_name": "Adaptabilité"},
    {"skills_name": "Gestion du temps"},
    {"skills_name": "Esprit critique"},
    {"skills_name": "Prise de décision"},
    {"skills_name": "Négociation"},
    {"skills_name": "Physique"},
    {"skills_name": "Mécanique"},
    {"skills_name": "Thermodynamique"},
    {"skills_name": "Électronique"},
    {"skills_name": "Matériaux"},
    {"skills_name": "Ingénierie des systèmes"},
    {"skills_name": "Automatisation"},
    {"skills_name": "Robotique"}
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
            offer_title VARCHAR(50) NOT NULL,
            offer_desc VARCHAR(255),
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
    # Vérifier si la table Details est vide
    cursor.execute("SELECT COUNT(*) FROM Details")
    count = cursor.fetchone()[0]

    # Récupérer le nombre d'offres existantes
    cursor.execute("SELECT offer_id FROM Offers")
    offer_ids = cursor.fetchall()  # Récupérer tous les offer_id existants

    if len(offer_ids) == 0:
        print("❌ Aucune offre disponible pour lier les détails.")
        return

    if count > 0:
        print("✅ 0 détails insérés dans Details.")
        return  # Si la table est remplie, ne pas insérer de nouvelles données
    
    # Si la table est vide, réinitialiser l'auto-incrémentation à 1
    cursor.execute("ALTER TABLE Details AUTO_INCREMENT = 1")

    # Insérer les détails dans la table Details
    inserted_count = 0
    for idx, detail in enumerate(details):
        offer_id = offer_ids[idx % len(offer_ids)][0]  # Associer les détails à une offre de manière cyclique

        cursor.execute(
            """
            INSERT INTO Details (offer_id, skills_id, detail)
            VALUES (%s, %s, %s)
            """, 
            (offer_id, detail["skills_id"], detail["detail"])  # Lier le détail à l'offre
        )
        inserted_count += cursor.rowcount  # Incrémente le nombre de lignes insérées

    conn.commit()  # Valider les changements dans la base de données
    print(f"✅ {inserted_count} détails insérés dans Details.")

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

except mysql.connector.Error as err:
    print(f"❌ Erreur MySQL : {err}")

finally:
    cursor.close()
    conn.close()
    print("🔌 Connexion fermée.")
