# Mindlet gRPC

## Fichiers générés

| Fichier         | Contenu principal                    | Usage principal                              |
| --------------- | ------------------------------------ | -------------------------------------------- |
| `*_pb2.py`      | Messages (structures de données)     | Créer, sérialiser et lire les messages gRPC  |
| `*_pb2_grpc.py` | Services gRPC (stubs client/serveur) | Implémenter le serveur et appeler le serveur |

## Option

| Niveau                        | Exemple                                                 | Utilité                                                                                        |
| ----------------------------- | ------------------------------------------------------- | ---------------------------------------------------------------------------------------------- |
| **Fichier entier**            | `option go_package = "pb";`                             | Indique le package Go du code généré. Peut aussi être `java_package`, `csharp_namespace`, etc. |
| **Message**                   | `option deprecated = true;`                             | Marque un message comme obsolète.                                                              |
| **Champ**                     | `string name = 1 [(deprecated) = true];`                | Marque un champ comme obsolète ou ajoute des métadonnées personnalisées.                       |
| **RPC / Service**             | `option (google.api.http) = { get: "/v1/users/{id}" };` | Configure la correspondance HTTP d’une méthode gRPC.                                           |
| **Personnalisée / Extension** | `(my_custom_option) = "valeur"`                         | Permet d’ajouter des métadonnées propres à ton projet.                                         |


## Types 

- `oneof` : Permet de définir un champ qui peut contenir une valeur parmi plusieurs types possibles. Utile pour les messages polymorphes. Un `oneof` ne peut jamais contenir plus d'un champ actif. Il sert à gérer des données alternatives (soit l’une, soit l’autre, jamais les deux).

- `google.protobuf.Timestamp` : Représente un instant précis dans le temps, avec une précision allant jusqu'à la nanoseconde. Utile pour les horodatages.