from pb.mindlet.pcbook import Cpu
import grpc
from concurrent import futures

import pb.mindlet.pcbook as CreateLaptop


def serve():
    server = grpc.server(futures.ThreadPoolExecutor(max_workers=10))
    CreateLaptop.add_LaptopService_to_server([ICI], server)
    server.add_insecure_port("[::]:50051")  
    server.start()
    print("🚀 Serveur gRPC lancé sur le port 50051")
    server.wait_for_termination()


if __name__ == "__main__":
    serve()
