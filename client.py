import asyncio
from datetime import datetime, timezone
from grpclib.client import Channel

from pb.mindlet.pcbook import (
    Laptop,
    LaptopServiceAsyncStub,
    CreateLaptopRequest,
    Cpu,
    Memory,
    MemoryUnit,
    Gpu,
    Storage,
    StorageDriver,
    Screen,
    ScreenResolution,
    ScreenPanel,
    Keyboard,
    KeyboardLayout,
)


async def test_client():
    channel = Channel(host="127.0.0.1", port=50051)
    stub = LaptopServiceAsyncStub(channel)

    cpu = Cpu(
        brand="Intel",
        name="Core i7",
        number_cores=8,
        number_threads=16,
        min_ghz=2.6,
        max_ghz=4.5,
    )

    ram = Memory(value=16, unit=MemoryUnit.KILOBYTE)

    gpu1 = Gpu(
        brand="NVIDIA",
        name="RTX 3060",
        min_ghz=1.2,
        max_ghz=1.8,
        memory=Memory(value=6, unit=MemoryUnit.GIGABYTE),
    )
    gpu2 = Gpu(
        brand="AMD",
        name="Radeon RX 6600M",
        min_ghz=1.5,
        max_ghz=2.3,
        memory=Memory(value=8, unit=MemoryUnit.GIGABYTE),
    )

    storage1 = Storage(
        driver=StorageDriver.SSD, memory=Memory(value=512, unit=MemoryUnit.GIGABYTE)
    )
    storage2 = Storage(
        driver=StorageDriver.HDD, memory=Memory(value=1, unit=MemoryUnit.TERABYTE)
    )

    screen = Screen(
        size_inch=15.6,
        resolution=ScreenResolution(width=1920, height=1080),
        panel=ScreenPanel.IPS,
        multitouch=False,
    )

    keyboard = Keyboard(
        layout=KeyboardLayout.QWERTY,
        backlit=True,
    )

    laptop = Laptop(
        id="laptop-123",
        brand="Dell",
        name="XPS 15",
        cpu=cpu,
        ram=ram,
        gpus=[gpu1, gpu2],
        storages=[storage1, storage2],
        screen=screen,
        keyboard=keyboard,
        weight_kg=2.0,
        weight_lb=4.4,
        price_usd=1499.99,
        release_year=2023,
        updated_at=datetime(2023, 10, 1, 12, 0, 0, tzinfo=timezone.utc),
    )

    request = CreateLaptopRequest(laptop=laptop)
    response = await stub.create_laptop(request)
    print(f"Réponse du serveur : id={response.id}")

    channel.close()


if __name__ == "__main__":
    asyncio.run(test_client())
