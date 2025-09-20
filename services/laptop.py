from pb.mindlet.pcbook import (
    Laptop,
    CreateLaptopRequest,
    CreateLaptopResponse,
    LaptopServiceBase,
)
import traceback


class LaptopService(LaptopServiceBase):
    async def create_laptop(self, request: CreateLaptopRequest) -> CreateLaptopResponse:
        try:
            print(f"Requête reçue : {request}")
            laptop = request.laptop
            print(f"Laptop extrait : {laptop}")
            # Logique pour sauvegarder l'ordinateur portable dans une base de données ou autre
            print(f"Ordinateur portable créé : {laptop}")
            response = CreateLaptopResponse(id=laptop.id)
            print(f"Réponse créée : {response}")
            return response
        except Exception as e:
            print(f"ERREUR dans create_laptop: {e}")
            print(f"Traceback complet:")
            traceback.print_exc()
            raise
