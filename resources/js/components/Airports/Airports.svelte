<script>
    import { onMount } from 'svelte';

    let flights = [];
    let total_price = null;
    let airports = [];
    let airport_departure;
    let airport_arrival;
    
    const getAirport = (code) => {
        return airports.find(airport => airport.code === code).name
    }

    $: canSubmit = () => airport_departure !== "0" && airport_arrival !== "0";

    const handleSubmit = async () => {

        try {
            const response = await fetch(`/ajax/flights/${airport_departure.id}/${airport_arrival.id}`);
            const data = await response.json();
            flights = data.flights;
            total_price = data.total_price;
        } catch {
            alert("Ops! We couldn't find flights to your destination")
        }

    }

    
    
    onMount(async () => {
		const res = await fetch('/ajax/airports');
		const data = await res.json();
        airports = data.airports;
	});

</script>


<div>

    {#if airports}

        <form on:submit|preventDefault={handleSubmit}>
            
            <div class="grid">
                <select bind:value={airport_departure} >
                    <option default value="0">Where from?</option>
                    {#each airports as airport}
                        {#if airport.id !== airport_arrival.id}
                            <option value={airport}>
                                {airport.name}
                            </option>
                        {/if}
                    {/each}
                </select>
    
                <select bind:value={airport_arrival} >
                    <option default value="0">Where to?</option>
                    {#each airports as airport}
                        {#if airport.id !== airport_departure.id}
                            <option value={airport}>
                                {airport.name}
                            </option>
                        {/if}
                    {/each}
                </select>
    
                <button class="{canSubmit() ? '' : 'disabled'}" type=submit>
                    Submit
                </button>
            </div>        
        

        </form>
    
    {/if}



        {#if flights.length}
            <p>
                <strong>Number of flights:</strong> {flights.length}
                <br>
                <strong>Total price:</strong> {total_price.toFixed(2)}
            </p>

        {#each flights as flight} 
            <div>
                <p>
                    <strong>From:</strong> {getAirport(flight.code_departure)}, 
                    <strong>to:</strong> {getAirport(flight.code_arrival)}
                    <br>
                    <strong>Price:</strong> {flight.price}
                </p>
            </div>
        {/each}

        {/if}

</div>


<style>
    select {
        padding: 7px 12px;
        background-color: #eee;
        border-radius: 3px;
    }

    button {
        padding: 7px 12px;
        background-color: #007dbc;
        color: white;
        border-radius: 3px;
        border: none;
    }

    button:hover {
        cursor:pointer;
    }

    button.disabled {
        background-color: #ccc;
        color: #aaa;
        cursor: not-allowed;
    }

    form {
        margin-top: 30px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: 1fr 1fr 1fr;
        row-gap: 15px;
    }


    @media(min-width: 801px) {
        .grid {
            grid-template-columns: 1fr 1fr 100px;
            grid-template-rows: 1fr;
            column-gap: 15px;
        }
    }




</style>


