<style>
    .crm-card {
        border: none;
        border-radius: 15px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .crm-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .crm-card-body {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 2;
    }
    /* Gradients matching the image style */
    .bg-gradient-pending {
        background: linear-gradient(135deg, #7F7FD5 0%, #86A8E7 50%, #91EAE4 100%); /* Purple-ish */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Deep Purple */
    }
    .bg-gradient-processing {
        background: linear-gradient(135deg, #2Af598 0%, #009efd 100%); /* Green-Blue */
    }
    .bg-gradient-resolved {
        background: linear-gradient(135deg, #1fa2ff 0%, #12d8fa 100%, #a6ffcb 100%); /* Blue-ish */
        background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); /* Blue */
    }
    .bg-gradient-rejected {
        background: linear-gradient(135deg, #ff512f 0%, #dd2476 100%); /* Red-Pink */
    }
    .bg-gradient-active {
        background: linear-gradient(135deg, #1fa2ff 0%, #12d8fa 100%, #a6ffcb 100%); /* Same as resolved for consistency or different? Using Resolved/Blue-ish for active */
    }
    .bg-gradient-inactive {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); /* Different gradient for inactive? Or sticking to info style */
        background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%); /* Blue variation */
    }
    .bg-gradient-suspended {
        background: linear-gradient(135deg, #ff9966 0%, #ff5e62 100%); /* Orange-Red */
    }
    
    .card-icon-box {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(5px);
    }
    .card-title-text {
        font-size: 0.9rem;
        font-weight: 600;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        text-transform: capitalize;
    }
    .card-amount {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0px;
    }
    /* Decorative circle */
    .crm-card::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        top: -30px;
        right: -30px;
        z-index: 1;
    }
</style>
