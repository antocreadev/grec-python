from pb.mindlet.pcbook import Cpu

cpu = Cpu(
    brand="Intel",
    name="Core i7-10750H",
    number_cores=6,
    number_threads=12,
    min_ghz=2.6,
    max_ghz=5.0,
)


if __name__ == "__main__":
    print(cpu)
