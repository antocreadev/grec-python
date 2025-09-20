import asyncio

from grpclib.utils import graceful_exit
from grpclib.server import Server

from services.laptop import LaptopService


async def main(*, host="127.0.0.1", port=50051):
    server = Server([LaptopService()])
    # Note: graceful_exit isn't supported in Windows
    with graceful_exit([server]):
        await server.start(host, port)
        print(f"Serving on {host}:{port}")
        await server.wait_closed()


if __name__ == "__main__":
    asyncio.run(main())
